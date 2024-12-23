<?php

namespace App\Http\Controllers;

use App\Domain\Report\DataTransferObjects\CreateReportDto;
use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Services\Interfaces\ReportServiceInterface;
use App\Http\Requests\CreateReportRequest;
use App\Http\Resources\ReportCollection;
use App\Http\Resources\ReportResource;
use App\Traits\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ReportController extends Controller
{
    use ApiResponse;

    public function __construct(private ReportServiceInterface $reportService)
    {
    }

    public function create(CreateReportRequest $request): JsonResponse
    {
        try {
            $validatedRequest = $request->safe()->all();
            $newReport = $this->reportService->create(
                CreateReportDto::from(
                    array_merge(
                        $validatedRequest,
                        [
                            'report_status_id' => ReportStatusEnum::PENDING->value
                        ]
                    )
                )
            );

            return $this->sendSuccessResponse(
                message: 'Solicitação de relatório criada com sucesso.',
                data: new ReportResource($newReport),
                code: Response::HTTP_CREATED
            );
        } catch (Throwable $exception) {
            Log::error(
                '[ReportController] Failed to create a new report solicitation.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'data' => [
                        'received_data' => $request->all() ?? null
                    ],
                    'stack_trace' => $exception->getTrace()
                ]
            );

            return $this->sendErrorResponse(
                message: 'Ocorreu um erro. Não foi possível criar uma nova solicitação de relatório conforme solicitado.',
                code: $exception->getCode()
            );
        }
    }

    public function index(): JsonResponse
    {
        try {
            $reports = $this->reportService->getAll();

            return $this->sendSuccessResponse(
                data: new ReportCollection($reports),
                code: Response::HTTP_OK
            );
        } catch (Throwable $exception) {
            Log::error(
                '[ReportController] Failed to get list of reports.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'stack_trace' => $exception->getTrace()
                ]
            );

            return $this->sendErrorResponse(
                message: 'Ocorreu um erro. Não foi possível obter a lista de relatórios conforme solicitado.',
                code: $exception->getCode()
            );
        }
    }

    public function download(string $id): JsonResponse|StreamedResponse
    {
        try {
            ['file_name' => $name, 'file_stream' => $stream] = $this->reportService->getReportFileStream((int) $id);

            return response()->streamDownload(function () use ($stream) {
                while (!feof($stream)) {
                    echo fread($stream, 1024 * 8); // Lê o arquivo em pedaços de 8 KB
                    flush();
                }
                fclose($stream);
            }, $name);
        } catch (Throwable $exception) {
            Log::error(
                '[ReportController] Failed to download the report file.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'data' => [
                        'report_id' => $id ?? null
                    ],
                    'stack_trace' => $exception->getTrace()
                ]
            );

            return $this->sendErrorResponse(
                message: 'Ocorreu um erro. Não foi possível efetuar o download do arquivo do relatório conforme solicitado.',
                code: $exception->getCode()
            );
        }
    }
}
