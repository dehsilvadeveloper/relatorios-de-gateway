<?php

namespace Database\Factories;

use App\Domain\GatewayLog\Models\GatewayLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class GatewayLogFactory extends Factory
{
    protected $model = GatewayLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => fake()->uuid(),
            'service_name' => fake()->word(),
            'consumer_id' => fake()->uuid(),
            'latency_proxy' => fake()->numberBetween(1, 3000),
            'latency_gateway' => fake()->numberBetween(1, 3000),
            'latency_request' => fake()->numberBetween(1, 3000),
            'raw_log' => $this->generateRawLog()
        ];
    }

    private function generateRawLog(): string
    {
        return json_encode([
            "request" => [
                "method" => "GET",
                "uri" => "/get",
                "url" => "http://httpbin.org:8000/get",
                "size" => "75",
                "querystring" => [],
                "headers" => [
                    "accept" => "*/*",
                    "host" => "httpbin.org",
                    "user-agent" => "curl/7.37.1"
                ],
            ],
            "upstream_uri" => "/",
            "response" => [
                "status" => 200,
                "size" => "434",
                "headers" => [
                    "Content-Length" => "197",
                    "via" => "gateway/0.3.0",
                    "Connection" => "close",
                    "access-control-allow-credentials" => "true",
                    "Content-Type" => "application/json",
                    "server" => "nginx",
                    "access-control-allow-origin" => "*"
                ]
            ],
            "authenticated_entity" => [
                "consumer_id" => $this->faker->uuid
            ],
            "route" => [
                "created_at" => 1521555129,
                "hosts" => null,
                "id" => "75818c5f-202d-4b82-a553-6a46e7c9a19e",
                "methods" => ["GET", "POST", "PUT", "DELETE", "PATCH", "OPTIONS", "HEAD"],
                "paths" => ["/example-path"],
                "preserve_host" => false,
                "protocols" => ["http", "https"],
                "regex_priority" => 0,
                "service" => [
                    "id" => $this->faker->uuid
                ],
                "strip_path" => true,
                "updated_at" => 1521555129
            ],
            "service" => [
                "connect_timeout" => 60000,
                "created_at" => 1521554518,
                "host" => "example.com",
                "id" => $this->faker->uuid,
                "name" => "myservice",
                "path" => "/",
                "port" => 80,
                "protocol" => "http",
                "read_timeout" => 60000,
                "retries" => 5,
                "updated_at" => 1521554518,
                "write_timeout" => 60000
            ],
            "latencies" => [
                "proxy" => $this->faker->numberBetween(1, 3000),
                "gateway" => $this->faker->numberBetween(1, 3000),
                "request" => $this->faker->numberBetween(1, 3000)
            ],
            "client_ip" => "127.0.0.1",
            "started_at" => 1433209822425
        ]);
    }
}
