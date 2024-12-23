<?php

namespace App\Domain\Report\Providers;

use App\Domain\Report\Services\Interfaces\ReportGenerationServiceInterface;
use App\Domain\Report\Services\Interfaces\ReportStatusServiceInterface;
use App\Domain\Report\Services\Interfaces\ReportTypeServiceInterface;
use App\Domain\Report\Services\ReportGenerationService;
use App\Domain\Report\Services\ReportStatusService;
use App\Domain\Report\Services\ReportTypeService;
use Illuminate\Support\ServiceProvider;

class ReportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->bindServiceClasses();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
    }

    /**
     * Bind service classes
     *
     * @return void
     */
    private function bindServiceClasses(): void
    {
        $this->app->bind(ReportGenerationServiceInterface::class, ReportGenerationService::class);
        $this->app->bind(ReportStatusServiceInterface::class, ReportStatusService::class);
        $this->app->bind(ReportTypeServiceInterface::class, ReportTypeService::class);
    }
}
