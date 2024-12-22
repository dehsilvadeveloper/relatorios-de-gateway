<?php

namespace App\Domain\GatewayLog\Repositories;

use App\Infrastructure\Database\Eloquent\Interfaces\RepositoryEloquentInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

interface GatewayLogRepositoryInterface extends RepositoryEloquentInterface
{
    public function getTotalRequestsByConsumer(bool $returnBuilder = false): Builder|Collection;

    public function getTotalRequestsByService(bool $returnBuilder = false): Builder|Collection;

    public function getLatenciesAverageTimeByService(bool $returnBuilder = false): Builder|Collection;
}
