<?php

namespace App\Domain\Report\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ReportStatusServiceInterface
{
    public function getAll(array $columns = ['*']): Collection;
}
