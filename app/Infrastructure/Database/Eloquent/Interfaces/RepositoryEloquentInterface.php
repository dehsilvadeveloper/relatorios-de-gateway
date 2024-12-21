<?php

namespace App\Infrastructure\Database\Eloquent\Interfaces;

use App\Domain\Common\DataTransferObjects\BaseDto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryEloquentInterface
{
    public function create(BaseDto $dto): ?Model;

    public function update(int $modelId, BaseDto $dto): Model;

    public function getAll(array $columns = ['*']): Collection;

    public function firstById(int $modelId, array $columns = ['*'], array $relations = []): ?Model;
}