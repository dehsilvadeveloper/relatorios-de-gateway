<?php

namespace Tests\TestHelpers\DataTransferObjects;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ModelConfigurationAssertionParamsDto extends Data
{
    public function __construct(
        public Model $model,
        public array $fillable = [],
        public array $hidden = [],
        public array $guarded = ['*'],
        public array $visible = [],
        public array $casts = ['id' => 'int'],
        public array $dates = ['created_at', 'updated_at'],
        public string $collectionClass = Collection::class,
        public ?string $table = null,
        public string $primaryKey = 'id'
    ) {
    }
}
