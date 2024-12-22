<?php

namespace Tests\Unit\App\Infrastructure\Database\Eloquent;

use App\Domain\Report\DataTransferObjects\CreateReportDto;
use App\Domain\Report\DataTransferObjects\UpdateReportDto;
use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Models\Report;
use App\Infrastructure\Database\Eloquent\ReportRepositoryEloquent;
use Database\Seeders\ReportStatusSeeder;
use Database\Seeders\ReportTypeSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ReportRepositoryEloquentTest extends TestCase
{
    /** @var ReportRepositoryEloquent */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ReportStatusSeeder::class);
        $this->seed(ReportTypeSeeder::class);

        $this->repository = app(ReportRepositoryEloquent::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @group repositories
     * @group report
     */
    public function test_can_create(): void
    {
        $data = Report::factory()->make()->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['generated_at']);

        $createdRecord = $this->repository->create(
            CreateReportDto::from($data)
        );

        $this->assertInstanceOf(Report::class, $createdRecord);
        $this->assertDatabaseHas('reports', $data);
    }

    /**
     * @group repositories
     * @group report
     */
    public function test_cannot_create_without_data(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You did not provide any data to create the record.');

        /** @var MockInterface|CreateReportDto $dtoMock */
        $dtoMock = Mockery::mock(CreateReportDto::class);
        $dtoMock->shouldReceive('toArray')->andReturn([]);

        $this->repository->create($dtoMock);
    }

    /**
     * @group repositories
     * @group report
     */
    public function test_can_update(): void
    {
        $existingRecord = Report::factory()->create([
            'report_status_id' => ReportStatusEnum::PENDING->value
        ]);

        $dataForUpdate = [
            'report_status_id' => ReportStatusEnum::COMPLETED->value,
        ];

        $updatedRecord = $this->repository->update(
            $existingRecord->id,
            UpdateReportDto::from([
                'report_status_id' => $dataForUpdate['report_status_id']
            ])
        );

        $this->assertInstanceOf(Report::class, $updatedRecord);
        $this->assertEquals($existingRecord->id, $updatedRecord->id);
        $this->assertEquals($dataForUpdate['report_status_id'], $updatedRecord->report_status_id);
    }

    /**
     * @group repositories
     * @group report
     */
    public function test_cannot_update_without_data(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You did not provide any data to update the record.');

        $existingRecord = Report::factory()->create([
            'report_status_id' => ReportStatusEnum::PENDING->value
        ]);

        /** @var MockInterface|UpdateReportDto $dtoMock */
        $dtoMock = Mockery::mock(UpdateReportDto::class);
        $dtoMock->shouldReceive('toArray')->andReturn([]);

        $this->repository->update($existingRecord->id, $dtoMock);
    }

    /**
     * @group repositories
     * @group report
     */
    public function test_cannot_update_a_nonexistent_record(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(
            9999,
            UpdateReportDto::from([
                'report_status_id' => ReportStatusEnum::COMPLETED->value
            ])
        );
    }

    /**
     * @group repositories
     * @group report
     */
    public function test_can_get_list_of_records(): void
    {
        $recordsCount = 3;

        $generatedRecords = Report::factory()->count($recordsCount)->create();
        $sortedGeneratedRecords = $generatedRecords->sortByDesc('id');
        $sortedGeneratedRecords = $sortedGeneratedRecords->values();
        $generatedRecordsAsArray = $sortedGeneratedRecords->toArray();

        $records = $this->repository->getAll();
        $recordsAsArray = $records->toArray();

        $this->assertCount($recordsCount, $records);

        for ($i = 0; $i <= ($recordsCount - 1); $i++) {
            $this->assertEquals($generatedRecordsAsArray[$i]['report_status_id'], $recordsAsArray[$i]['report_status_id']);
            $this->assertEquals($generatedRecordsAsArray[$i]['report_type_id'], $recordsAsArray[$i]['report_type_id']);
            $this->assertEquals($generatedRecordsAsArray[$i]['filename'], $recordsAsArray[$i]['filename']);
            $this->assertEquals($generatedRecordsAsArray[$i]['generated_at'], $recordsAsArray[$i]['generated_at']);
        }
    }

    /**
     * @group repositories
     * @group report
     */
    public function test_can_get_empty_list_of_records(): void
    {
        $records = $this->repository->getAll();

        $this->assertCount(0, $records);
        $this->assertTrue($records->isEmpty());
    }

    /**
     * @group repositories
     * @group report
     */
    public function test_can_find_by_id(): void
    {
        $existingRecord = Report::factory()->create();

        $foundRecord = $this->repository->firstById($existingRecord->id);

        $this->assertInstanceOf(Report::class, $foundRecord);
        $this->assertEquals($existingRecord->report_status_id, $foundRecord->report_status_id);
        $this->assertEquals($existingRecord->report_type_id, $foundRecord->report_type_id);
        $this->assertEquals($existingRecord->filename, $foundRecord->filename);
        $this->assertEquals($existingRecord->generated_at, $foundRecord->generated_at);
    }

    /**
     * @group repositories
     * @group report
     */
    public function test_cannot_find_by_id_a_nonexistent_record(): void
    {
        $foundRecord = $this->repository->firstById(1);

        $this->assertNull($foundRecord);
    }
}
