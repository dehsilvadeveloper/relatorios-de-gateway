<?php

namespace Database\Factories;

use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Enums\ReportTypeEnum;
use App\Domain\Report\Models\Report;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = Carbon::now();

        return [
            'report_status_id' => fake()->randomElement(ReportStatusEnum::cases())->value,
            'report_type_id' => fake()->randomElement(ReportTypeEnum::cases())->value,
            'filename' => strtolower(fake()->word()) . '.csv',
            'created_at' => $now,
            'updated_at' => $now,
            'generated_at' => null
        ];
    }
}
