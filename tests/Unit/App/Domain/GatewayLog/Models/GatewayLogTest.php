<?php

namespace Tests\Unit\App\Domain\GatewayLog\Models;

use Tests\ModelTestCase;
use Tests\TestHelpers\DataTransferObjects\ModelConfigurationAssertionParamsDto;
use App\Domain\GatewayLog\Models\GatewayLog;

class GatewayLogTest extends ModelTestCase
{
    /**
     * @group models
     * @group gateway_log
     */
    public function test_has_valid_configuration(): void
    {
        $dto = ModelConfigurationAssertionParamsDto::from([
            'model' => new GatewayLog(),
            'fillable' => [
                'service_id',
                'service_name',
                'consumer_id',
                'latency_proxy',
                'latency_gateway',
                'latency_request',
                'raw_log'
            ],
            'hidden' => [],
            'casts' => [
                'id' => 'int'
            ],
            'table' => 'gateway_logs'
        ]);

        $this->runConfigurationAssertions($dto);
    }
}
