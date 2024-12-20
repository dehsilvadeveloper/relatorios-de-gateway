<?php

namespace App\Infrastructure\Database\Eloquent\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Common\DataTransferObjects\BaseDto;

interface RepositoryEloquentInterface
{
    public function create(BaseDto $dto): ?Model;

    public function getAll(array $columns = ['*']): Collection;

    public function firstById(int $modelId, array $columns = ['*'], array $relations = []): ?Model;
}