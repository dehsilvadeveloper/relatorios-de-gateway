<?php

namespace Tests\Unit\Database\Seeders;

use Database\Seeders\ReportStatusSeeder;
use Tests\TestCase;

class ReportStatusSeederTest extends TestCase
{
    /**
     * @group seeders
     * @group report
     */
    public function test_can_seed_report_statuses_into_database(): void
    {
        $this->seed(ReportStatusSeeder::class);

        foreach (config('report_statuses.default') as $reportStatus) {
            $this->assertDatabaseHas('report_statuses', $reportStatus);
        }
    }
}
