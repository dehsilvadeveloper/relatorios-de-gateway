<?php

namespace Database\Seeders;

use App\Domain\Report\Models\ReportType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reportTypes = collect(config('report_types.default'));

        if ($reportTypes->isEmpty()) {
            Log::info('[ReportTypeSeeder] No data was found to be seeded on the table report_types.');
            return;
        }

        DB::beginTransaction();

        try {
            foreach ($reportTypes as $reportType) {
                ReportType::updateOrCreate(
                    ['id' => $reportType['id']],
                    $reportType
                );
            }

            DB::commit();

            $this->command->info('Table report_types seeded.');
        } catch (Throwable $exception) {
            DB::rollBack();

            Log::error(
                '[ReportTypeSeeder] Error while executing seeder ReportTypeSeeder.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'stack_trace' => $exception->getTrace()
                ]
            );

            $this->command->error('Table report_types seeding failed.');
        }
    }
}
