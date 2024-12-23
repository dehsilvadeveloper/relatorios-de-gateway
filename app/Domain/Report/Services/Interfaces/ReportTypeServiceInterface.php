<?php

namespace App\Domain\Report\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ReportTypeServiceInterface
{
    public function getAll(array $columns = ['*']): Collection;
}
