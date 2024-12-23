<?php

namespace Tests\Feature\Report;

use App\Domain\Report\Models\ReportStatus;
use Illuminate\Http\Response;
use Tests\TestCase;

class ListReportStatusTest extends TestCase
{
    /**
     * @group report
     */
    public function test_can_get_list_of_records(): void
    {
        $recordsCount = 3;
        ReportStatus::factory()->count($recordsCount)->create();

        $response = $this->getJson(route('report-status.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name'
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
        $response = $this->getJson(route('report-status.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertJsonCount(0, 'data');
    }
}
