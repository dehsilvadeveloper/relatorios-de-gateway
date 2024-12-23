<?php

namespace Tests\Feature\Report;

use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Models\Report;
use Database\Seeders\ReportStatusSeeder;
use Database\Seeders\ReportTypeSeeder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadReportTest extends TestCase
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
    public function test_can_download_a_report_file(): void
    {
        Storage::fake('local');

        $filename = 'report_1.csv';
        $filePath = 'generated_reports/' . $filename;
        $fileContent = 'Fake report data';
        Storage::put($filePath, $fileContent);

        $existingRecord = Report::factory()->create([
            'report_status_id' => ReportStatusEnum::COMPLETED->value,
            'filename' => $filename
        ]);

        $response = $this->get(route('report.download', ['id' => $existingRecord->id]));
        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Disposition', 'attachment; filename=' . $filename);

        ob_start();
        $response->send();
        $output = ob_get_clean();

        $this->assertEquals($fileContent, $output);
    }

    /**
     * @group report
     */
    public function test_cannot_download_if_report_does_not_exist_on_database(): void
    {
        $response = $this->get(route('report.download', ['id' => 9999]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @group report
     */
    public function test_cannot_download_if_file_does_not_exist(): void
    {
        $existingRecord = Report::factory()->create([
            'report_status_id' => ReportStatusEnum::COMPLETED->value,
            'filename' => 'nonexistent_file.csv'
        ]);

        $response = $this->get(route('report.download', ['id' => $existingRecord->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
