<?php

namespace Database\Factories;

use App\Domain\Report\Models\ReportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportStatusFactory extends Factory
{
    protected $model = ReportStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word()
        ];
    }
}
