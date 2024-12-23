<?php

namespace App\Domain\Report\Services;

use App\Domain\Report\Repositories\ReportTypeRepositoryInterface;
use App\Domain\Report\Services\Interfaces\ReportTypeServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportTypeService implements ReportTypeServiceInterface
{
    public function __construct(private ReportTypeRepositoryInterface $reportTypeRepository)
    {
    }

    public function getAll(array $columns = ['*']): Collection
    {
        try {
            return $this->reportTypeRepository->getAll($columns);
        } catch (Throwable $exception) {
            Log::error(
                '[ReportTypeService] Failed to get a list of report types.',
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
