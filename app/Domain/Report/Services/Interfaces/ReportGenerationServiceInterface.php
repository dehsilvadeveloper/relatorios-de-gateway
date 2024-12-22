<?php

namespace App\Domain\Report\Services\Interfaces;

use App\Domain\Report\Models\Report;

interface ReportGenerationServiceInterface
{
    public function generate(Report $record): bool;
}
