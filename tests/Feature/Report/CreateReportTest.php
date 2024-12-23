<?php

namespace Tests\Feature\Report;

use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Enums\ReportTypeEnum;
use Database\Seeders\ReportStatusSeeder;
use Database\Seeders\ReportTypeSeeder;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateReportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ReportStatusSeeder::class);
        $this->seed(ReportTypeSeeder::class);
    }

    /**
     * @group report
     */
    public function test_can_create(): void
    {
        $data = [
            'report_type_id' => ReportTypeEnum::LATENCIES_AVERAGE_TIME_BY_SERVICE->value
        ];

        $response = $this->postJson(route('report.create'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'type' => [
                    'id',
                    'name'
                ],
                'status' => [
                    'id',
                    'name'
                ],
                'filename'
            ]
        ]);
        $response->assertJson([
            'message' => 'Solicitação de relatório criada com sucesso.',
            'data' => [
                'type' => [
                    'id' => $data['report_type_id'],
                    'name' => ReportTypeEnum::LATENCIES_AVERAGE_TIME_BY_SERVICE->name(),
                ],
                'status' => [
                    'id' => ReportStatusEnum::PENDING->value,
                    'name' => ReportStatusEnum::PENDING->name(),
                ]
            ]
        ]);
    }

    /**
     * @group report
     */
    public function test_cannot_create_without_data(): void
    {
        $response = $this->postJson(route('report.create'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'report_type_id'
            ]
        ]);
        $response->assertJson([
            'errors' => [
                'report_type_id' => ['The report type id field is required.']
            ]
        ]);
    }

    /**
     * @group report
     */
    public function test_cannot_create_with_invalid_data(): void
    {
        $data = [
            'report_type_id' => 9999
        ];

        $response = $this->postJson(route('report.create'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'report_type_id'
            ]
        ]);
        $response->assertJson([
            'errors' => [
                'report_type_id' => ['The selected report type id is invalid.']
            ]
        ]);
    }
}
