<?php

namespace Tests\Unit\Database\Factories;

use Tests\TestCase;
use App\Domain\GatewayLog\Models\GatewayLog;

class GatewayLogFactoryTest extends TestCase
{
    /**
     * @group factories
     * @group gateway_log
     */
    public function test_can_create_a_model(): void
    {
        $model = GatewayLog::factory()->make();

        $this->assertInstanceOf(GatewayLog::class, $model);
    }
}