<?php

namespace App\Console\Commands;

use App\Domain\GatewayLog\Repositories\GatewayLogRepositoryInterface;
use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Models\Report;
use App\Domain\Report\Repositories\ReportRepositoryInterface;
use Exception;
use Illuminate\Console\Command;
use Throwable;

class GenerateReportsCommand extends Command
{
    /**
     * Usage example:
     *
     * php artisan reports:generate
     */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera relatórios em formato CSV com base em solicitações presentes na entidade reports';

    public function __construct(
        protected GatewayLogRepositoryInterface $gatewayLogRepository,
        protected ReportRepositoryInterface $reportRepository
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $reportFilePath = 'generated_reports/report.csv';
        $pendingReport = $this->getPendingReport();

        if (!$pendingReport) {
	        $this->info('Não existem relatórios para serem gerados. Finalizando execução.');
	        return Command::SUCCESS;
	    }

        try {
            $this->generateCsvReport($pendingReport);

            return Command::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Erro durante a a geração do relatório: ' . $exception->getMessage());

            return Command::FAILURE;
        }
    }

    private function getPendingReport(): ?Report
    {
        return $this->reportRepository->firstByField('report_status_id', ReportStatusEnum::PENDING->value);
    }

    private function generateCsvReport(Report $pendingReport)
    {
        return;
    }
}
