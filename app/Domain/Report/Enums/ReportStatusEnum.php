<?php

namespace App\Domain\Report\Enums;

enum ReportStatusEnum: int
{
    case PENDING = 1;
    case COMPLETED = 2;
    case ERROR = 3;

    public function name(): string
    {
        return match ($this) {
            self::PENDING => config('report_statuses.default.0.name'),
            self::COMPLETED => config('report_statuses.default.1.name'),
            self::ERROR => config('report_statuses.default.2.name')
        };
    }
}
