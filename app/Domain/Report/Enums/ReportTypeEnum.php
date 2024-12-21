<?php

namespace App\Domain\Report\Enums;

enum ReportTypeEnum: int
{
    case TOTAL_REQUESTS_BY_CONSUMER = 1;
    case TOTAL_REQUESTS_BY_SERVICE = 2;
    case LATENCIES_AVERAGE_TIME_BY_SERVICE = 3;

    public function name(): string
    {
        return match ($this) {
            self::TOTAL_REQUESTS_BY_CONSUMER => config('report_types.default.0.name'),
            self::TOTAL_REQUESTS_BY_SERVICE => config('report_types.default.1.name'),
            self::LATENCIES_AVERAGE_TIME_BY_SERVICE => config('report_types.default.2.name')
        };
    }
}
