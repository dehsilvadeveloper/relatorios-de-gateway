<?php

namespace App\Infrastructure\Database\Eloquent;

use App\Domain\Report\Models\ReportType;
use App\Domain\Report\Repositories\ReportTypeRepositoryInterface;

class ReportTypeRepositoryEloquent extends BaseRepositoryEloquent implements ReportTypeRepositoryInterface
{
    public function __construct(ReportType $model)
    {
        $this->model = $model;
    }
}
