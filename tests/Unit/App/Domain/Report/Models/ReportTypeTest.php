<?php

namespace Tests\Unit\App\Domain\Report\Models;

use App\Domain\Report\Models\ReportType;
use Tests\ModelTestCase;
use Tests\TestHelpers\DataTransferObjects\ModelConfigurationAssertionParamsDto;

class ReportTypeTest extends ModelTestCase
{
    /**
     * @group models
     * @group report
     */
    public function test_has_valid_configuration(): void
    {
        $dto = ModelConfigurationAssertionParamsDto::from([
            'model' => new ReportType(),
            'fillable' => [
                'name'
            ],
            'hidden' => [],
            'casts' => [
                'id' => 'int'
            ],
            'dates' => [],
            'table' => 'report_types'
        ]);

        $this->runConfigurationAssertions($dto);
    }
}
