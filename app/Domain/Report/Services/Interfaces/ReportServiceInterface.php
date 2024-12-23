<?php

namespace App\Domain\Report\Services\Interfaces;

use App\Domain\Report\DataTransferObjects\CreateReportDto;
use App\Domain\Report\Models\Report;
use Illuminate\Database\Eloquent\Collection;

interface ReportServiceInterface
{
    public function create(CreateReportDto $dto): ?Report;

    public function getAll(array $columns = ['*']): Collection;

    public function getReportFileStream(int $id): array;
}
