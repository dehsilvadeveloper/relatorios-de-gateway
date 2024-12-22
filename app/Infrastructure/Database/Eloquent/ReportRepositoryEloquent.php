<?php

namespace App\Infrastructure\Database\Eloquent;

use App\Domain\Report\Models\Report;
use App\Domain\Report\Repositories\ReportRepositoryInterface;

class ReportRepositoryEloquent extends BaseRepositoryEloquent implements ReportRepositoryInterface
{
    public function __construct(Report $model)
    {
        $this->model = $model;
    }
}
