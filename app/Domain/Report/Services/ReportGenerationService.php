<?php

namespace App\Domain\Report\Services;

use App\Domain\GatewayLog\Repositories\GatewayLogRepositoryInterface;
use App\Domain\Report\DataTransferObjects\UpdateReportDto;
use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Enums\ReportTypeEnum;
use App\Domain\Report\Models\Report;
use App\Domain\Report\Repositories\ReportRepositoryInterface;
use App\Domain\Report\Services\Interfaces\ReportGenerationServiceInterface;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class ReportGenerationService implements ReportGenerationServiceInterface
{
    public function __construct(
        private GatewayLogRepositoryInterface $gatewayLogRepository,
        private ReportRepositoryInterface $reportRepository
    ) {
    }

    public function generate(Report $report): bool
    {
        try {
            $filename = $this->generateCsv($report);

            $this->reportRepository->update(
                $report->id,
                UpdateReportDto::from([
                    'report_status_id' => ReportStatusEnum::COMPLETED->value,
                    'filename' => $filename,
                    'generated_at' => now()
                ])
            );

            return true;
        } catch (Throwable $exception) {
            Log::error(
                '[ReportGenerationService] Erro ao processar a geração de relatório em formato CSV.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'data' => [
                        'report_id' => $report->id ?? null
                    ],
                    'stack_trace' => $exception->getTrace()
                ]
            );

            $this->reportRepository->update(
                $report->id,
                UpdateReportDto::from([
                    'report_status_id' => ReportStatusEnum::ERROR->value
                ])
            );

            throw $exception;
        }
    }

    private function generateCsv(Report $report): string
    {
        Storage::disk('local')->makeDirectory('generated_reports');

        $filename = 'report_' . $report->id . '_' . Str::uuid() . '.csv';
        $filePath = 'generated_reports/' . $filename;
        $file = fopen(storage_path('app/' . $filePath), 'w');

        $processed = 0;

        try {
            $query = $this->getQueryForReportData($report->report_type_id);

            $query->chunk(1000, function ($reportContentLines) use ($file, &$processed) {
                if ($processed === 0 && $reportContentLines->isNotEmpty()) {
                    fputcsv($file, array_keys((array) $reportContentLines->first()), ';');
                }

                foreach ($reportContentLines as $reportContentLine) {
                    fputcsv($file, (array) $reportContentLine, ';');
                    $processed++;
                }
            });

            if (!Storage::disk('local')->exists($filePath)) {
                throw new Exception(
                    'Erro. O arquivo do relatório, nomeado ' . $filename . ', não pôde ser localizado. '
                        . 'Verifique os logs para saber se houveram falhas no processo de geração.'
                );
            }

            return $filename;
        } catch (Throwable $exception) {
            if (Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }

            throw $exception;
        } finally {
            fclose($file);
        }
    }

    private function getQueryForReportData(int $reportType): Builder
	{
		return match ($reportType) {
            ReportTypeEnum::TOTAL_REQUESTS_BY_CONSUMER->value => $this->getTotalRequestsByConsumerQuery(),
            ReportTypeEnum::TOTAL_REQUESTS_BY_SERVICE->value => $this->getTotalRequestsByServiceQuery(),
            ReportTypeEnum::LATENCIES_AVERAGE_TIME_BY_SERVICE->value => $this->getLatenciesAverageTimeByServiceQuery(),
            default => throw new InvalidArgumentException(
              'Tipo de relatório desconhecido. ID do tipo solicitado: '. $reportType
            )
        };
	}

    private function getTotalRequestsByConsumerQuery(): Builder
	{
		return $this->gatewayLogRepository->getTotalRequestsByConsumer(returnBuilder: true);
	}

	private function getTotalRequestsByServiceQuery(): Builder
	{
		return $this->gatewayLogRepository->getTotalRequestsByService(returnBuilder: true);
	}

	private function getLatenciesAverageTimeByServiceQuery(): Builder
	{
		return $this->gatewayLogRepository->getLatenciesAverageTimeByService(returnBuilder: true);
	}
}
