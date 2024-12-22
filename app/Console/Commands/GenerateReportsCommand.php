<?php

namespace App\Console\Commands;

use App\Domain\Report\Enums\ReportStatusEnum;
use App\Domain\Report\Repositories\ReportRepositoryInterface;
use App\Domain\Report\Services\Interfaces\ReportGenerationServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
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
        protected ReportGenerationServiceInterface $reportGenerationService,
        protected ReportRepositoryInterface $reportRepository
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $pendingReports = $this->getPendingReports();

        if ($pendingReports->isEmpty()) {
	        $this->info('Não existem relatórios para serem gerados. Finalizando execução.');
	        return Command::SUCCESS;
	    }

        try {
            $this->info('Iniciando geração de relatórios pendentes...' . PHP_EOL);

            foreach ($pendingReports as $pendingReport) {
                $this->line('Iniciando geração do relatório de ID #' . $pendingReport->id . '.' . PHP_EOL);

                $totalItems = $this->reportGenerationService->getReportTotalLines($pendingReport);
                $progressBar = $this->output->createProgressBar($totalItems);
                $progressBar->setFormat('%current%/%max% linhas inseridas [%bar%] %percent:3s%%');
                $progressBar->start();
    
                // Processamos a geração do relatório enquanto atualizamos a barra de progresso via callback
                $this->reportGenerationService->generate(
                    $pendingReport,
                    function ($processed) use ($progressBar) {
                        $progressBar->setProgress($processed);
                    }
                );
    
                $progressBar->finish();

                $this->line(PHP_EOL . 'Relatório de ID #' . $pendingReport->id . ' gerado com sucesso.' . PHP_EOL);
            }

            $this->info('Todos os relatórios pendentes de geração foram processados.' . PHP_EOL);

            return Command::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Erro durante geração de relatórios: ' . $exception->getMessage());

            return Command::FAILURE;
        }
    }

    private function getPendingReports(): Collection
    {
        return $this->reportRepository->getByField('report_status_id', ReportStatusEnum::PENDING->value);
    }
}
