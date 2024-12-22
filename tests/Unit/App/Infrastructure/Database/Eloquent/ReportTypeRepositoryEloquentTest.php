<?php

namespace Tests\Unit\App\Infrastructure\Database\Eloquent;

use App\Domain\Report\Models\ReportType;
use App\Infrastructure\Database\Eloquent\ReportTypeRepositoryEloquent;
use Mockery;
use Tests\TestCase;

class ReportTypeRepositoryEloquentTest extends TestCase
{
    /** @var ReportTypeRepositoryEloquent */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(ReportTypeRepositoryEloquent::class);
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

        $generatedRecords = ReportType::factory()->count($recordsCount)->create();
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
