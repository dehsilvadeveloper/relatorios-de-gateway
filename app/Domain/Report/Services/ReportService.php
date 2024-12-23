<?php

namespace App\Domain\Report\Services;

use App\Domain\Report\DataTransferObjects\CreateReportDto;
use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Exceptions\ReportFileGenerationIncompleteException;
use App\Domain\Report\Exceptions\ReportFileNotFoundException;
use App\Domain\Report\Exceptions\ReportNotFoundException;
use App\Domain\Report\Models\Report;
use App\Domain\Report\Repositories\ReportRepositoryInterface;
use App\Domain\Report\Services\Interfaces\ReportServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportService implements ReportServiceInterface
{
    public function __construct(private ReportRepositoryInterface $reportRepository)
    {
    }

    public function create(CreateReportDto $dto): ?Report
    {
        try {
            return $this->reportRepository->create($dto);
        } catch (Throwable $exception) {
            Log::error(
                '[ReportService] Failed to register a report solicitation with the data provided.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'data' => [
                        'received_dto' => $dto->toArray() ?? null
                    ],
                    'stack_trace' => $exception->getTrace()
                ]
            );

            throw $exception;
        }
    }

    public function getAll(array $columns = ['*']): Collection
    {
        try {
            return $this->reportRepository->getAll($columns);
        } catch (Throwable $exception) {
            Log::error(
                '[ReportService] Failed to get a list of reports.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'stack_trace' => $exception->getTrace()
                ]
            );

            throw $exception;
        }
    }

    public function getReportFileStream(int $id): array
    {
        try {
            $report = $this->reportRepository->firstById($id);

            if (!$report) {
                throw new ReportNotFoundException();
            }

            if ($report->report_status_id != ReportStatusEnum::COMPLETED->value) {
                throw new ReportFileGenerationIncompleteException();
            }

            $filePath = 'generated_reports/' . $report->filename;

            if (!Storage::disk('local')->exists($filePath)) {
                throw new ReportFileNotFoundException();
            }

            return [
                'file_name' => $report->filename,
                'file_stream' => Storage::disk('local')->readStream($filePath)
            ];
        } catch (Throwable $exception) {
            Log::error(
                '[ReportService] Cannot get report file stream.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'data' => [
                        'id' => $id ?? null
                    ],
                    'stack_trace' => $exception->getTrace()
                ]
            );

            throw $exception;
        }
    }
}
