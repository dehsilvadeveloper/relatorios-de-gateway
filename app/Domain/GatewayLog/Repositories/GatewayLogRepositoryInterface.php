<?php

namespace App\Domain\GatewayLog\Repositories;

use App\Infrastructure\Database\Eloquent\Interfaces\RepositoryEloquentInterface;
use Illuminate\Support\Collection;

interface GatewayLogRepositoryInterface extends RepositoryEloquentInterface
{
    public function getTotalRequestsByConsumer(): Collection;

    public function getTotalRequestsByService(): Collection;

    public function getLatenciesAverageTimeByService(): Collection;
}
