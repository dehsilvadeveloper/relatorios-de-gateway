<?php

namespace Tests\Unit\App\Domain\Report\Services;

use App\Domain\Report\DataTransferObjects\CreateReportDto;
use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Enums\ReportTypeEnum;
use App\Domain\Report\Exceptions\ReportFileGenerationIncompleteException;
use App\Domain\Report\Exceptions\ReportFileNotFoundException;
use App\Domain\Report\Exceptions\ReportNotFoundException;
use App\Domain\Report\Models\Report;
use App\Domain\Report\Repositories\ReportRepositoryInterface;
use App\Domain\Report\Services\ReportService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ReportServiceTest extends TestCase
{
    /** @var ReportService */
    private $service;

    /** @var MockInterface */
    private $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(ReportRepositoryInterface::class);
        $this->service = app(ReportService::class, ['reportRepository' => $this->repositoryMock]);
    }

    /**
     * @group services
     * @group report
     */
    public function test_can_create(): void
    {
        $reportData = [
            'report_status_id' => ReportStatusEnum::PENDING->value,
            'report_type_id' => ReportTypeEnum::TOTAL_REQUESTS_BY_CONSUMER->value
        ];
        $dto = CreateReportDto::from($reportData);
        $fakeRecord = Report::factory()->make($reportData);
        $fakeRecord->id = 10;

        $this->repositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($dto)
            ->andReturn($fakeRecord);

        $createdRecord = $this->service->create($dto);

        $this->assertInstanceOf(Report::class, $createdRecord);
        $this->assertEquals($fakeRecord->report_status_id, $createdRecord->report_status_id);
        $this->assertEquals($fakeRecord->report_type_id, $createdRecord->report_type_id);
        $this->assertEquals($fakeRecord->filename, $createdRecord->filename);
        $this->assertEquals($fakeRecord->generated_at, $createdRecord->generated_at);
    }

    /**
     * @group services
     * @group report
     */
    public function test_cannot_create_if_exception_occurs(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Houston, we have a problem.');

        $dto = CreateReportDto::from([
            'report_status_id' => ReportStatusEnum::PENDING->value,
            'report_type_id' => ReportTypeEnum::TOTAL_REQUESTS_BY_CONSUMER->value
        ]);

        Log::shouldReceive('error')
            ->withArgs(function ($message, $context) {
                return strpos(
                    $message,
                    '[ReportService] Failed to register a report solicitation with the data provided.'
                ) !== false
                    && strpos($context['error_message'], 'Houston, we have a problem.') !== false;
            });

        $this->repositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($dto)
            ->andThrows(new Exception('Houston, we have a problem.'));

        $this->service->create($dto);
    }

    /**
     * @group services
     * @group report
     */
    public function test_can_get_list_of_records(): void
    {
        $recordsCount = 3;
        $generatedRecords = Report::factory()->count($recordsCount)->make();
        $generatedRecordsAsArray = $generatedRecords->toArray();

        $this->repositoryMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($generatedRecords);

        $records = $this->service->getAll();
        $recordsAsArray = $records->toArray();

        $this->assertCount($recordsCount, $records);

        for ($i = 0; $i <= ($recordsCount - 1); $i++) {
            $this->assertEquals(
                $generatedRecordsAsArray[$i]['report_status_id'], $recordsAsArray[$i]['report_status_id']
            );
            $this->assertEquals($generatedRecordsAsArray[$i]['report_type_id'], $recordsAsArray[$i]['report_type_id']);
            $this->assertEquals($generatedRecordsAsArray[$i]['filename'], $recordsAsArray[$i]['filename']);
            $this->assertEquals($generatedRecordsAsArray[$i]['generated_at'], $recordsAsArray[$i]['generated_at']);
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
                return strpos($message, '[ReportService] Failed to get a list of reports.') !== false
                    && strpos($context['error_message'], 'Houston, we have a problem.') !== false;
            });

        $this->repositoryMock
            ->shouldReceive('getAll')
            ->once()
            ->andThrows(new Exception('Houston, we have a problem.'));

        $this->service->getAll();
    }

    /**
     * @group services
     * @group report
     */
    public function test_can_get_report_file_stream(): void
    {
        $filename = 'example.csv';
        $filePath = 'generated_reports/' . $filename;
        $fakeRecord = Report::factory()->make([
            'report_status_id' => ReportStatusEnum::COMPLETED->value,
            'filename' => $filename,
        ]);
        $fakeRecord->id = 5;

        $this->repositoryMock
            ->shouldReceive('firstById')
            ->once()
            ->with($fakeRecord->id)
            ->andReturn($fakeRecord);

        Storage::shouldReceive('disk')
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('exists')
            ->once()
            ->with($filePath)
            ->andReturn(true);

        Storage::shouldReceive('readStream')
            ->once()
            ->with($filePath)
            ->andReturn('stream content');

        $response = $this->service->getReportFileStream($fakeRecord->id);

        $this->assertIsArray($response);
        $this->assertEquals($filename, $response['file_name']);
        $this->assertEquals('stream content', $response['file_stream']);
    }

    /**
     * @group services
     * @group report
     */
    public function test_cannot_get_report_file_stream_when_report_not_found(): void
    {
        $this->expectException(ReportNotFoundException::class);

        $this->repositoryMock
            ->shouldReceive('firstById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $this->service->getReportFileStream(999);
    }

    /**
     * @group services
     * @group report
     */
    public function test_cannot_get_report_file_stream_when_report_generation_incomplete(): void
    {
        $this->expectException(ReportFileGenerationIncompleteException::class);

        $fakeRecord = Report::factory()->make([
            'report_status_id' => ReportStatusEnum::PENDING->value,
            'filename' => 'example.csv'
        ]);
        $fakeRecord->id = 4;

        $this->repositoryMock
            ->shouldReceive('firstById')
            ->once()
            ->with($fakeRecord->id)
            ->andReturn($fakeRecord);

        $this->service->getReportFileStream($fakeRecord->id);
    }

    /**
     * @group services
     * @group report
     */
    public function test_cannot_get_report_file_stream_when_file_not_found(): void
    {
        $this->expectException(ReportFileNotFoundException::class);

        $filename = 'example.csv';
        $filePath = 'generated_reports/' . $filename;
        $fakeRecord = Report::factory()->make([
            'id' => 1,
            'report_status_id' => ReportStatusEnum::COMPLETED->value,
            'filename' => $filename,
        ]);
        $fakeRecord->id = 7;

        $this->repositoryMock
            ->shouldReceive('firstById')
            ->once()
            ->with($fakeRecord->id)
            ->andReturn($fakeRecord);

        Storage::shouldReceive('disk')
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('exists')
            ->once()
            ->with($filePath)
            ->andReturn(false);

        $this->service->getReportFileStream($fakeRecord->id);
    }
}
