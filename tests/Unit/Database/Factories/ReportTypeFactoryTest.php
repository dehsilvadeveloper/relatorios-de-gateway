<?php

namespace Tests\Unit\Database\Factories;

use App\Domain\Report\Models\ReportType;
use Tests\TestCase;

class ReportTypeFactoryTest extends TestCase
{
    /**
     * @group factories
     * @group report
     */
    public function test_can_create_a_model(): void
    {
        $model = ReportType::factory()->make();

        $this->assertInstanceOf(ReportType::class, $model);
    }
}
