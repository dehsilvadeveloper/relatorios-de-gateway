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
            foreach ($pendingReports as $pendingReport) {
                $this->reportGenerationService->generate($pendingReport);

                $this->info('Relatório de ID #' . $pendingReport->id . ' processado com sucesso.');
            }

            $this->info('Todos os relatórios pendentes de geração foram processados.');

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
