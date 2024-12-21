<?php

namespace Database\Seeders;

use App\Domain\Report\Models\ReportStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportStatusSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reportStatuses = collect(config('report_statuses.default'));

        if ($reportStatuses->isEmpty()) {
            Log::info('[ReportStatusSeeder] No data was found to be seeded on the table report_statuses.');
            return;
        }

        DB::beginTransaction();

        try {
            foreach ($reportStatuses as $reportStatus) {
                ReportStatus::updateOrCreate(
                    ['id' => $reportStatus['id']],
                    $reportStatus
                );
            }

            DB::commit();

            $this->command->info('Table report_statuses seeded.');
        } catch (Throwable $exception) {
            DB::rollBack();

            Log::error(
                '[ReportStatusSeeder] Error while executing seeder ReportStatusSeeder.',
                [
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'stack_trace' => $exception->getTrace()
                ]
            );

            $this->command->error('Table report_statuses seeding failed.');
        }
    }
}
