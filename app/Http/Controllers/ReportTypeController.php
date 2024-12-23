<?php

namespace App\Http\Controllers;

use App\Domain\Report\Services\Interfaces\ReportTypeServiceInterface;
use App\Http\Resources\ReportTypeCollection;
use App\Traits\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportTypeController extends Controller
{
    use ApiResponse;

    public function __construct(private ReportTypeServiceInterface $reportTypeService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $reportTypes = $this->reportTypeService->getAll();

            return $this->sendSuccessResponse(
                data: new ReportTypeCollection($reportTypes),
                code: Response::HTTP_OK
            );
        } catch (Throwable $exception) {
            Log::error(
                '[ReportTypeController] Failed to get list of report types.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'stack_trace' => $exception->getTrace()
                ]
            );

            return $this->sendErrorResponse(
                message: 'Ocorreu um erro. Não foi possível obter a lista de tipos de relatório conforme solicitado.',
                code: $exception->getCode()
            );
        }
    }
}
