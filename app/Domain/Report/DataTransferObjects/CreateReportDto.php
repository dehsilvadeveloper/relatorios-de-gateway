<?php

namespace App\Domain\Report\DataTransferObjects;

use App\Domain\Common\DataTransferObjects\BaseDto;
use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Enums\ReportTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Optional;

class CreateReportDto extends BaseDto
{
    public function __construct(
        #[WithCast(EnumCast::class)]
        public ReportStatusEnum $reportStatusId,
        #[WithCast(EnumCast::class)]
        public ReportTypeEnum $reportTypeId,
        public string|Optional $filename,
        public string|Optional $generatedAt
    ) {
    }
}
