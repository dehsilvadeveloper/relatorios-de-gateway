<?php

namespace Tests\Unit\App\Domain\GatewayLog\DataTransferObjects;

use App\Domain\GatewayLog\DataTransferObjects\CreateGatewayLogDto;
use App\Domain\GatewayLog\Models\GatewayLog;
use Illuminate\Http\Request;
use Tests\DtoTestCase;

class CreateGatewayLogDtoTest extends DtoTestCase
{
    /**
     * @group dtos
     * @group gateway_log
     */
    public function test_can_create_from_array_with_snakecase_keys(): void
    {
        $this->runCreationFromSnakecaseArrayAssertions(
            CreateGatewayLogDto::class,
            $this->getValidData()
        );
    }

    /**
     * @group dtos
     * @group gateway_log
     */
    public function test_can_create_from_array_with_camelcase_keys(): void
    {
        $this->runCreationFromCamelcaseArrayAssertions(
            CreateGatewayLogDto::class,
            $this->getValidData(useCamelCaseKeys: true)
        );
    }

    /**
     * @group dtos
     * @group gateway_log
     */
    public function test_cannot_create_from_empty_array(): void
    {
        $this->runCreationFromEmptyArrayAssertions(CreateGatewayLogDto::class);
    }

    /**
     * @group dtos
     * @group gateway_log
     */
    public function test_can_create_from_request(): void
    {
        $this->runCreationFromRequestAssertions(
            CreateGatewayLogDto::class,
            Request::create(
                '/dummy',
                'POST',
                $this->getValidData()
            )
        );
    }

    /**
     * @group dtos
     * @group gateway_log
     */
    public function test_cannot_create_from_empty_request(): void
    {
        $this->runCreationFromEmptyRequestAssertions(CreateGatewayLogDto::class);
    }

    /**
     * @group dtos
     * @group gateway_log
     */
    public function test_cannot_create_from_request_with_invalid_values(): void
    {
        $this->runCreationFromRequestWithInvalidValuesAssertions(
            CreateGatewayLogDto::class,
            Request::create(
                '/dummy',
                'POST',
                $this->getInvalidData()
            )
        );
    }

    private function getValidData(bool $useCamelCaseKeys = false): array
    {
        $validData = GatewayLog::factory()->make()->toArray();

        return (!$useCamelCaseKeys) ? $validData : [
            'serviceId' => $validData['service_id'],
            'serviceName' => $validData['service_name'],
            'consumerId' => $validData['consumer_id'],
            'latencyProxy' => $validData['latency_proxy'],
            'latencyGateway' => $validData['latency_gateway'],
            'latencyRequest' => $validData['latency_request'],
            'rawLog' => $validData['raw_log']
        ];
    }

    private function getInvalidData(): array
    {
        return GatewayLog::factory()->make([
            'latency_proxy' => 'abc'
        ])->toArray();
    }
}
