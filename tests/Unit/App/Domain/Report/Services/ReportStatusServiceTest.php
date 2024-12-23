<?php

namespace Tests\Unit\App\Domain\Report\Services;

use App\Domain\Report\Models\ReportStatus;
use App\Domain\Report\Repositories\ReportStatusRepositoryInterface;
use App\Domain\Report\Services\ReportStatusService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ReportStatusServiceTest extends TestCase
{
    /** @var ReportStatusService */
    private $service;

    /** @var MockInterface */
    private $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(ReportStatusRepositoryInterface::class);
        $this->service = app(ReportStatusService::class, ['reportStatusRepository' => $this->repositoryMock]);
    }

    /**
     * @group services
     * @group report
     */
    public function test_can_get_list_of_records(): void
    {
        $recordsCount = 3;
        $generatedRecords = ReportStatus::factory()->count($recordsCount)->make();
        $generatedRecordsAsArray = $generatedRecords->toArray();

        $this->repositoryMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($generatedRecords);

        $records = $this->service->getAll();
        $recordsAsArray = $records->toArray();

        $this->assertCount($recordsCount, $records);

        for ($i = 0; $i <= ($recordsCount - 1); $i++) {
            $this->assertEquals($generatedRecordsAsArray[$i]['name'], $recordsAsArray[$i]['name']);
        }
    }

    /**
     * @group services
     * @group report
     */
    public function test_can_get_empty_list_of_records(): void
    {
        $this->repositoryMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn(new Collection());

        $records = $this->service->getAll();

        $this->assertCount(0, $records);
        $this->assertTrue($records->isEmpty());
    }

    /**
     * @group services
     * @group report
     */
    public function test_cannot_get_list_of_records_if_exception_occurs(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Houston, we have a problem.');

        Log::shouldReceive('error')
            ->withArgs(function ($message, $context) {
                return strpos($message, '[ReportStatusService] Failed to get a list of report statuses.') !== false
                    && strpos($context['error_message'], 'Houston, we have a problem.') !== false;
            });

        $this->repositoryMock
            ->shouldReceive('getAll')
            ->once()
            ->andThrows(new Exception('Houston, we have a problem.'));

        $this->service->getAll();
    }
}
