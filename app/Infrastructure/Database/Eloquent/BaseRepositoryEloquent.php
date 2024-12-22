<?php

namespace App\Infrastructure\Database\Eloquent;

use App\Domain\Common\DataTransferObjects\BaseDto;
use App\Infrastructure\Database\Eloquent\Interfaces\RepositoryEloquentInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Response;
use InvalidArgumentException;

class BaseRepositoryEloquent implements RepositoryEloquentInterface
{
    /** @var Model|EloquentBuilder|QueryBuilder|SoftDeletes */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(BaseDto $dto): ?Model
    {
        $data = $dto->toArray();

        if (empty($data)) {
            throw new InvalidArgumentException(
                'You did not provide any data to create the record.',
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->model->create($data);
    }

    public function update(int $modelId, BaseDto $dto): Model
    {
        $data = $dto->toArray();

        if (empty($data)) {
            throw new InvalidArgumentException(
                'You did not provide any data to update the record.',
                Response::HTTP_BAD_REQUEST
            );
        }

        $item = $this->model->findOrFail($modelId);
        $item->update($data);
        $item->refresh();

        return $item;
    }

    public function getAll(array $columns = ['*']): Collection
    {
        return $this->model->orderBy('id', 'desc')->get($columns);
    }

    public function getByField(string $field, mixed $value, array $columns = ['*']): Collection
    {
        return $this->model->where($field, '=', $value)->get($columns);
    }

    public function firstById(int $modelId, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->find($modelId);
    }

    public function firstByField(string $field, mixed $value, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->where($field, '=', $value)->first();
    }
}
