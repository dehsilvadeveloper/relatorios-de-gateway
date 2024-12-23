<?php

namespace App\Domain\Report\Services;

use App\Domain\Report\Repositories\ReportStatusRepositoryInterface;
use App\Domain\Report\Services\Interfaces\ReportStatusServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportStatusService implements ReportStatusServiceInterface
{
    public function __construct(private ReportStatusRepositoryInterface $reportStatusRepository)
    {
    }

    public function getAll(array $columns = ['*']): Collection
    {
        try {
            return $this->reportStatusRepository->getAll($columns);
        } catch (Throwable $exception) {
            Log::error(
                '[ReportStatusService] Failed to get a list of report statuses.',
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
}
