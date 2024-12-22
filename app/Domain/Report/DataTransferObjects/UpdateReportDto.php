<?php

namespace App\Domain\Report\DataTransferObjects;

use App\Domain\Common\DataTransferObjects\BaseDto;
use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Enums\ReportTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Optional;

class UpdateReportDto extends BaseDto
{
    public function __construct(
        #[WithCast(EnumCast::class)]
        public ReportStatusEnum|Optional $reportStatusId,
        #[WithCast(EnumCast::class)]
        public ReportTypeEnum|Optional $reportTypeId,
        public string|Optional $filename,
        public string|Optional $generatedAt
    ) {
    }
}
