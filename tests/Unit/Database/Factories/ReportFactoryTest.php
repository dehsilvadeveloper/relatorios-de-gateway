<?php

namespace Tests\Unit\Database\Factories;

use App\Domain\Report\Models\Report;
use Tests\TestCase;

class ReportFactoryTest extends TestCase
{
    /**
     * @group factories
     * @group report
     */
    public function test_can_create_a_model(): void
    {
        $model = Report::factory()->make();

        $this->assertInstanceOf(Report::class, $model);
    }
}
