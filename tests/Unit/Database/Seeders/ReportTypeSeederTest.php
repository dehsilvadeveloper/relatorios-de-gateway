<?php

namespace Tests\Unit\Database\Seeders;

use Database\Seeders\ReportTypeSeeder;
use Tests\TestCase;

class ReportTypeSeederTest extends TestCase
{
    /**
     * @group seeders
     * @group report
     */
    public function test_can_seed_report_types_into_database(): void
    {
        $this->seed(ReportTypeSeeder::class);

        foreach (config('report_types.default') as $reportType) {
            $this->assertDatabaseHas('report_types', $reportType);
        }
    }
}
