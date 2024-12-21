<?php

namespace Tests\Unit\App\Domain\Report\Models;

use App\Domain\Report\Models\Report;
use Tests\ModelTestCase;
use Tests\TestHelpers\DataTransferObjects\ModelConfigurationAssertionParamsDto;

class ReportTest extends ModelTestCase
{
    /**
     * @group models
     * @group report
     */
    public function test_has_valid_configuration(): void
    {
        $dto = ModelConfigurationAssertionParamsDto::from([
            'model' => new Report(),
            'fillable' => [
                'report_status_id',
                'report_type_id',
                'filename',
                'generated_at'
            ],
            'hidden' => [],
            'casts' => [
                'id' => 'int',
                'generated_at' => 'datetime'
            ],
            'table' => 'reports'
        ]);

        $this->runConfigurationAssertions($dto);
    }
}
