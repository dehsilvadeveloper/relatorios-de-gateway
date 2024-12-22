<?php

namespace App\Infrastructure\Database\Eloquent;

use App\Domain\Report\Models\ReportStatus;
use App\Domain\Report\Repositories\ReportStatusRepositoryInterface;

class ReportStatusRepositoryEloquent extends BaseRepositoryEloquent implements ReportStatusRepositoryInterface
{
    public function __construct(ReportStatus $model)
    {
        $this->model = $model;
    }
}
