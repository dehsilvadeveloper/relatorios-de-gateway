<?php

namespace Tests\Unit\App\Infrastructure\Database\Eloquent;

use App\Domain\Report\Models\ReportStatus;
use App\Infrastructure\Database\Eloquent\ReportStatusRepositoryEloquent;
use Mockery;
use Tests\TestCase;

class ReportStatusRepositoryEloquentTest extends TestCase
{
    /** @var ReportStatusRepositoryEloquent */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(ReportStatusRepositoryEloquent::class);
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
    public function test_can_get_list_of_records(): void
    {
        $recordsCount = 3;

        $generatedRecords = ReportStatus::factory()->count($recordsCount)->create();
        $sortedGeneratedRecords = $generatedRecords->sortByDesc('id');
        $sortedGeneratedRecords = $sortedGeneratedRecords->values();
        $generatedRecordsAsArray = $sortedGeneratedRecords->toArray();

        $records = $this->repository->getAll();
        $recordsAsArray = $records->toArray();

        $this->assertCount($recordsCount, $records);

        for ($i = 0; $i <= ($recordsCount - 1); $i++) {
            $this->assertEquals($generatedRecordsAsArray[$i]['name'], $recordsAsArray[$i]['name']);
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
}
