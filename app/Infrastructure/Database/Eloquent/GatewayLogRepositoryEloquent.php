<?php

namespace App\Infrastructure\Database\Eloquent;

use App\Domain\GatewayLog\Models\GatewayLog;
use App\Domain\GatewayLog\Repositories\GatewayLogRepositoryInterface;

class GatewayLogRepositoryEloquent extends BaseRepositoryEloquent implements GatewayLogRepositoryInterface
{
    public function __construct(GatewayLog $model)
    {
        $this->model = $model;
    }
}
