<?php

namespace App\Infrastructure\Database\Eloquent;

use App\Domain\GatewayLog\Models\GatewayLog;
use App\Domain\GatewayLog\Repositories\GatewayLogRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GatewayLogRepositoryEloquent extends BaseRepositoryEloquent implements GatewayLogRepositoryInterface
{
    public function __construct(GatewayLog $model)
    {
        $this->model = $model;
    }

    public function getTotalRequestsByConsumer(): Collection
    {
        return DB::table('gateway_logs')
            ->select('consumer_id', DB::raw('COUNT(*) AS total_requests'))
            ->groupBy('consumer_id')
            ->get();
            //->toRawSql();
    }

    public function getTotalRequestsByService(): Collection
    {
        return DB::table('gateway_logs')
            ->select('service_id', 'service_name', DB::raw('COUNT(*) AS total_requests'))
            ->groupBy('service_id', 'service_name')
            ->get();
    }

    public function getLatenciesAverageTimeByService(): Collection
    {
        return DB::table('gateway_logs')
            ->select(
                'service_id',
                'service_name',
                DB::raw('AVG(latency_proxy) AS avg_time_latency_proxy'),
                DB::raw('AVG(latency_gateway) AS avg_time_latency_gateway'),
                DB::raw('AVG(latency_request) AS avg_time_latency_request')
            )
            ->groupBy('service_id', 'service_name')
            ->get();
    }
}
