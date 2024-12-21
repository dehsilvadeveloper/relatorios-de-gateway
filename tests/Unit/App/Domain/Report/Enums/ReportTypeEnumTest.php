<?php

namespace Tests\Unit\App\Domain\Report\Enums;

use App\Domain\Report\Enums\ReportTypeEnum;
use Tests\TestCase;

class ReportTypeEnumTest extends TestCase
{
    /**
     * @group enums
     * @group report
     */
    public function test_can_get_enum_values(): void
    {
        $this->assertEquals(1, ReportTypeEnum::TOTAL_REQUESTS_BY_CONSUMER->value);
        $this->assertEquals(2, ReportTypeEnum::TOTAL_REQUESTS_BY_SERVICE->value);
        $this->assertEquals(3, ReportTypeEnum::LATENCIES_AVERAGE_TIME_BY_SERVICE->value);
    }

    /**
     * @group enums
     * @group report
     */
    public function test_can_get_enum_names(): void
    {
        $this->assertEquals(
            config('report_types.default.0.name'),
            ReportTypeEnum::TOTAL_REQUESTS_BY_CONSUMER->name()
        );
        $this->assertEquals(
            config('report_types.default.1.name'),
            ReportTypeEnum::TOTAL_REQUESTS_BY_SERVICE->name()
        );
        $this->assertEquals(
            config('report_types.default.2.name'),
            ReportTypeEnum::LATENCIES_AVERAGE_TIME_BY_SERVICE->name()
        );
    }
}
