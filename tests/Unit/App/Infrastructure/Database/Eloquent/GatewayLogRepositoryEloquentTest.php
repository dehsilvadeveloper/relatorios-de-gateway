<?php

namespace Tests\Unit\App\Infrastructure\Database\Eloquent;

use App\Domain\GatewayLog\DataTransferObjects\CreateGatewayLogDto;
use App\Domain\GatewayLog\Models\GatewayLog;
use App\Infrastructure\Database\Eloquent\GatewayLogRepositoryEloquent;
use InvalidArgumentException;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class GatewayLogRepositoryEloquentTest extends TestCase
{
    /** @var GatewayLogRepositoryEloquent */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(GatewayLogRepositoryEloquent::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_create(): void
    {
        $data = GatewayLog::factory()->make()->toArray();

        $createdRecord = $this->repository->create(
            CreateGatewayLogDto::from($data)
        );

        $this->assertInstanceOf(GatewayLog::class, $createdRecord);
        $this->assertDatabaseHas('gateway_logs', $data);
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_cannot_create_without_data(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You did not provide any data to create the record.');

        /** @var MockInterface|CreateGatewayLogDto $dtoMock */
        $dtoMock = Mockery::mock(CreateGatewayLogDto::class);
        $dtoMock->shouldReceive('toArray')->andReturn([]);

        $this->repository->create($dtoMock);
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_get_list_of_records(): void
    {
        $recordsCount = 3;

        $generatedRecords = GatewayLog::factory()->count($recordsCount)->create();
        $sortedGeneratedRecords = $generatedRecords->sortByDesc('id');
        $sortedGeneratedRecords = $sortedGeneratedRecords->values();
        $generatedRecordsAsArray = $sortedGeneratedRecords->toArray();

        $records = $this->repository->getAll();
        $recordsAsArray = $records->toArray();

        $this->assertCount($recordsCount, $records);

        for ($i = 0; $i <= ($recordsCount - 1); $i++) {
            $this->assertEquals($generatedRecordsAsArray[$i]['service_id'], $recordsAsArray[$i]['service_id']);
            $this->assertEquals($generatedRecordsAsArray[$i]['service_name'], $recordsAsArray[$i]['service_name']);
            $this->assertEquals($generatedRecordsAsArray[$i]['consumer_id'], $recordsAsArray[$i]['consumer_id']);
            $this->assertEquals($generatedRecordsAsArray[$i]['latency_proxy'], $recordsAsArray[$i]['latency_proxy']);
            $this->assertEquals($generatedRecordsAsArray[$i]['latency_gateway'], $recordsAsArray[$i]['latency_gateway']);
            $this->assertEquals($generatedRecordsAsArray[$i]['latency_request'], $recordsAsArray[$i]['latency_request']);
            $this->assertEquals($generatedRecordsAsArray[$i]['raw_log'], $recordsAsArray[$i]['raw_log']);
        }
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_get_empty_list_of_records(): void
    {
        $records = $this->repository->getAll();

        $this->assertCount(0, $records);
        $this->assertTrue($records->isEmpty());
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_find_by_id(): void
    {
        $existingRecord = GatewayLog::factory()->create();

        $foundRecord = $this->repository->firstById($existingRecord->id);

        $this->assertInstanceOf(GatewayLog::class, $foundRecord);
        $this->assertEquals($existingRecord->service_id, $foundRecord->service_id);
        $this->assertEquals($existingRecord->service_name, $foundRecord->service_name);
        $this->assertEquals($existingRecord->consumer_id, $foundRecord->consumer_id);
        $this->assertEquals($existingRecord->latency_proxy, $foundRecord->latency_proxy);
        $this->assertEquals($existingRecord->latency_gateway, $foundRecord->latency_gateway);
        $this->assertEquals($existingRecord->latency_request, $foundRecord->latency_request);
        $this->assertEquals($existingRecord->raw_log, $foundRecord->raw_log);
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_cannot_find_by_id_a_nonexistent_record(): void
    {
        $foundRecord = $this->repository->firstById(1);

        $this->assertNull($foundRecord);
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_get_total_requests_by_consumer(): void
    {
        GatewayLog::factory()->create(['consumer_id' => 1]);
        GatewayLog::factory()->create(['consumer_id' => 1]);
        GatewayLog::factory()->create(['consumer_id' => 2]);

        $records = $this->repository->getTotalRequestsByConsumer();

        $this->assertCount(2, $records);
        $this->assertEquals(2, $records->firstWhere('consumer_id', '=', 1)->total_requests);
        $this->assertEquals(1, $records->firstWhere('consumer_id', '=', 2)->total_requests);
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_get_empty_total_requests_by_consumer(): void
    {
        $records = $this->repository->getTotalRequestsByConsumer();

        $this->assertCount(0, $records);
        $this->assertTrue($records->isEmpty());
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_get_total_requests_by_service(): void
    {
        GatewayLog::factory()->create(['service_id' => 1, 'service_name' => 'Service A']);
        GatewayLog::factory()->create(['service_id' => 2, 'service_name' => 'Service B']);
        GatewayLog::factory()->create(['service_id' => 2, 'service_name' => 'Service B']);

        $records = $this->repository->getTotalRequestsByService();

        $this->assertCount(2, $records);
        $this->assertEquals(1, $records->firstWhere('service_id', '=', 1)->total_requests);
        $this->assertEquals(2, $records->firstWhere('service_id', '=', 2)->total_requests);
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_get_empty_total_requests_by_service(): void
    {
        $records = $this->repository->getTotalRequestsByService();

        $this->assertCount(0, $records);
        $this->assertTrue($records->isEmpty());
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_get_latencies_average_time_by_service(): void
    {
        GatewayLog::factory()->create([
            'service_id' => 1,
            'service_name' => 'Service A',
            'latency_proxy' => 100,
            'latency_gateway' => 200,
            'latency_request' => 300
        ]);
        GatewayLog::factory()->create([
            'service_id' => 1,
            'service_name' => 'Service A',
            'latency_proxy' => 200,
            'latency_gateway' => 300,
            'latency_request' => 400
        ]);
        GatewayLog::factory()->create([
            'service_id' => 2,
            'service_name' => 'Service B',
            'latency_proxy' => 150,
            'latency_gateway' => 250,
            'latency_request' => 350
        ]);

        $records = $this->repository->getLatenciesAverageTimeByService();
        $latenciesServiceA = $records->firstWhere('service_id', 1);
        $latenciesServiceB = $records->firstWhere('service_id', 2);

        $this->assertCount(2, $records);

        $this->assertEquals(150, $latenciesServiceA->avg_time_latency_proxy);
        $this->assertEquals(250, $latenciesServiceA->avg_time_latency_gateway);
        $this->assertEquals(350, $latenciesServiceA->avg_time_latency_request);

        $this->assertEquals(150, $latenciesServiceB->avg_time_latency_proxy);
        $this->assertEquals(250, $latenciesServiceB->avg_time_latency_gateway);
        $this->assertEquals(350, $latenciesServiceB->avg_time_latency_request);
    }

    /**
     * @group repositories
     * @group gateway_log
     */
    public function test_can_get_empty_latencies_average_time_by_service(): void
    {
        $records = $this->repository->getLatenciesAverageTimeByService();

        $this->assertCount(0, $records);
        $this->assertTrue($records->isEmpty());
    }
}
