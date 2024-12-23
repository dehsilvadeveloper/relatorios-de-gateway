<?php

namespace Tests\Unit\App\Domain\Report\Services;

use App\Domain\GatewayLog\Repositories\GatewayLogRepositoryInterface;
use App\Domain\Report\DataTransferObjects\UpdateReportDto;
use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Enums\ReportTypeEnum;
use App\Domain\Report\Models\Report;
use App\Domain\Report\Repositories\ReportRepositoryInterface;
use App\Domain\Report\Services\ReportGenerationService;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ReportGenerationServiceTest extends TestCase
{
    /** @var ReportGenerationService */
    private $service;

    /** @var MockInterface */
    private $gatewayLogRepositoryMock;

    /** @var MockInterface */
    private $reportRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gatewayLogRepositoryMock = Mockery::mock(GatewayLogRepositoryInterface::class);
        $this->reportRepositoryMock = Mockery::mock(ReportRepositoryInterface::class);
        $this->service = app(
            ReportGenerationService::class,
            [
                'gatewayLogRepository' => $this->gatewayLogRepositoryMock,
                'reportRepository' => $this->reportRepositoryMock
            ]
        );
    }

    /**
     * @group services
     * @group report
     * @group report_generation
     */
    public function test_can_generate_report_file(): void
    {
        $report = Report::factory()->make([
            'report_status_id' => ReportStatusEnum::PENDING->value,
            'report_type_id' => ReportTypeEnum::TOTAL_REQUESTS_BY_CONSUMER->value
        ]);
        $report->id = 10;

        $this->gatewayLogRepositoryMock->shouldReceive('getTotalRequestsByConsumer')
            ->once()
            ->andReturn(Mockery::mock(Builder::class, function (MockInterface $mock) {
                $mock->shouldReceive('chunk')
                    ->once()
                    ->with(1000, Mockery::type('callable'))
                    ->andReturnUsing(function ($chunkSize, $callback) {
                        $lines = collect([
                            (object) ['column1' => 'value1', 'column2' => 'value2'],
                            (object) ['column1' => 'value3', 'column2' => 'value4']
                        ]);

                        $callback($lines);
                        return true;
                    });
            }));

        $this->reportRepositoryMock->shouldReceive('update')
            ->once()
            ->with($report->id, Mockery::type(UpdateReportDto::class))
            ->andReturn(Mockery::mock(Report::class));

        Storage::shouldReceive('disk')
            ->twice()
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('makeDirectory')
            ->once()
            ->with('generated_reports');

        Storage::shouldReceive('exists')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturnTrue();

        $response = $this->service->generate($report);

        $this->assertTrue($response);
    }

    /**
     * @group services
     * @group report
     * @group report_generation
     */
    public function test_fail_to_generate_report_file(): void
    {
        $this->expectException(Exception::class);

        $report = Report::factory()->make([
            'report_status_id' => ReportStatusEnum::PENDING->value,
            'report_type_id' => ReportTypeEnum::TOTAL_REQUESTS_BY_CONSUMER->value
        ]);
        $report->id = 10;

        $this->gatewayLogRepositoryMock->shouldReceive('getTotalRequestsByConsumer')
            ->once()
            ->andReturn(Mockery::mock(Builder::class, function (MockInterface $mock) {
                $mock->shouldReceive('chunk')
                    ->once()
                    ->with(1000, Mockery::type('callable'))
                    ->andReturnUsing(function ($chunkSize, $callback) {
                        $lines = collect([
                            (object) ['column1' => 'value1', 'column2' => 'value2'],
                            (object) ['column1' => 'value3', 'column2' => 'value4']
                        ]);

                        $callback($lines);
                        return true;
                    });
            }));

        $this->reportRepositoryMock->shouldReceive('update')
            ->once()
            ->with($report->id, Mockery::type(UpdateReportDto::class))
            ->andReturn(Mockery::mock(Report::class));

        Storage::shouldReceive('disk')
            ->times(3)
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('makeDirectory')
            ->once()
            ->with('generated_reports');

        Storage::shouldReceive('exists')
            ->twice()
            ->with(Mockery::type('string'))
            ->andReturnFalse();

        Log::shouldReceive('error')
            ->once();

        $this->service->generate($report);
    }
}
