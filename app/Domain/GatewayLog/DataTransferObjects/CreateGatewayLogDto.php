<?php

namespace App\Domain\GatewayLog\DataTransferObjects;

use App\Domain\Common\DataTransferObjects\BaseDto;

class CreateGatewayLogDto extends BaseDto
{
    public function __construct(
        public string $serviceId,
        public string $serviceName,
        public string $consumerId,
        public int $latencyProxy,
        public int $latencyGateway,
        public int $latencyRequest,
        public string $rawLog
    ) {
    }
}
