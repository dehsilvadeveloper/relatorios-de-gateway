<?php

namespace Tests\Unit\App\Domain\Report\Enums;

use App\Domain\Report\Enums\ReportStatusEnum;
use Tests\TestCase;

class ReportStatusEnumTest extends TestCase
{
    /**
     * @group enums
     * @group report
     */
    public function test_can_get_enum_values(): void
    {
        $this->assertEquals(1, ReportStatusEnum::PENDING->value);
        $this->assertEquals(2, ReportStatusEnum::COMPLETED->value);
        $this->assertEquals(3, ReportStatusEnum::ERROR->value);
    }

    /**
     * @group enums
     * @group report
     */
    public function test_can_get_enum_names(): void
    {
        $this->assertEquals(
            config('report_statuses.default.0.name'),
            ReportStatusEnum::PENDING->name()
        );
        $this->assertEquals(
            config('report_statuses.default.1.name'),
            ReportStatusEnum::COMPLETED->name()
        );
        $this->assertEquals(
            config('report_statuses.default.2.name'),
            ReportStatusEnum::ERROR->name()
        );
    }
}
