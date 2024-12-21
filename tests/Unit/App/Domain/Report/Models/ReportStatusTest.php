<?php

namespace Tests\Unit\App\Domain\Report\Models;

use App\Domain\Report\Models\ReportStatus;
use Tests\ModelTestCase;
use Tests\TestHelpers\DataTransferObjects\ModelConfigurationAssertionParamsDto;

class ReportStatusTest extends ModelTestCase
{
    /**
     * @group models
     * @group report
     */
    public function test_has_valid_configuration(): void
    {
        $dto = ModelConfigurationAssertionParamsDto::from([
            'model' => new ReportStatus(),
            'fillable' => [
                'name'
            ],
            'hidden' => [],
            'casts' => [
                'id' => 'int'
            ],
            'dates' => [],
            'table' => 'report_statuses'
        ]);

        $this->runConfigurationAssertions($dto);
    }
}
