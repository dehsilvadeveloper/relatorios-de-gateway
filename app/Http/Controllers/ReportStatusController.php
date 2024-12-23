<?php

namespace App\Http\Controllers;

use App\Domain\Report\Services\Interfaces\ReportStatusServiceInterface;
use App\Http\Resources\ReportStatusCollection;
use App\Traits\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportStatusController extends Controller
{
    use ApiResponse;

    public function __construct(private ReportStatusServiceInterface $reportStatusService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $reportStatuses = $this->reportStatusService->getAll();

            return $this->sendSuccessResponse(
                data: new ReportStatusCollection($reportStatuses),
                code: Response::HTTP_OK
            );
        } catch (Throwable $exception) {
            Log::error(
                '[ReportStatusController] Failed to get list of report statuses.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'stack_trace' => $exception->getTrace()
                ]
            );

            return $this->sendErrorResponse(
                message: 'An error has occurred. Could not get the report statuses list as requested.',
                code: $exception->getCode()
            );
        }
    }
}
