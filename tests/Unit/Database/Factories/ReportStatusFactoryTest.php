<?php

namespace Tests\Unit\Database\Factories;

use App\Domain\Report\Models\ReportStatus;
use Tests\TestCase;

class ReportStatusFactoryTest extends TestCase
{
    /**
     * @group factories
     * @group report
     */
    public function test_can_create_a_model(): void
    {
        $model = ReportStatus::factory()->make();

        $this->assertInstanceOf(ReportStatus::class, $model);
    }
}
