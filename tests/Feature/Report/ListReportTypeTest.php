<?php

namespace Tests\Feature\Report;

use App\Domain\Report\Models\ReportType;
use Illuminate\Http\Response;
use Tests\TestCase;

class ListReportTypeTest extends TestCase
{
    /**
     * @group report
     */
    public function test_can_get_list_of_records(): void
    {
        $recordsCount = 3;
        ReportType::factory()->count($recordsCount)->create();

        $response = $this->getJson(route('report-type.index'));

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
        $response = $this->getJson(route('report-type.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertJsonCount(0, 'data');
    }
}
