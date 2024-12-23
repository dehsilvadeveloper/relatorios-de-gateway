<?php

namespace Tests\Feature\Report;

use App\Domain\Report\Models\Report;
use Database\Seeders\ReportStatusSeeder;
use Database\Seeders\ReportTypeSeeder;
use Illuminate\Http\Response;
use Tests\TestCase;

class ListReportTest extends TestCase
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
    public function test_can_get_list_of_records(): void
    {
        $recordsCount = 3;
        Report::factory()->count($recordsCount)->create();

        $response = $this->getJson(route('report.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
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
            ]
        ]);
        $response->assertJsonCount($recordsCount, 'data');
    }

    /**
     * @group report
     */
    public function test_can_get_empty_list_of_records(): void
    {
        $response = $this->getJson(route('report.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertJsonCount(0, 'data');
    }
}
