<?php

namespace App\Domain\Report\Providers;

use App\Domain\Report\Services\Interfaces\ReportGenerationServiceInterface;
use App\Domain\Reports\Services\ReportGenerationService;
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
    }
}
