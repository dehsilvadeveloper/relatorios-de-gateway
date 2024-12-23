<?php

namespace App\Infrastructure\Database\Eloquent\Providers;

use App\Domain\GatewayLog\Repositories\GatewayLogRepositoryInterface;
use App\Domain\Report\Repositories\ReportRepositoryInterface;
use App\Domain\Report\Repositories\ReportStatusRepositoryInterface;
use App\Domain\Report\Repositories\ReportTypeRepositoryInterface;
use App\Infrastructure\Database\Eloquent\BaseRepositoryEloquent;
use App\Infrastructure\Database\Eloquent\GatewayLogRepositoryEloquent;
use App\Infrastructure\Database\Eloquent\Interfaces\RepositoryEloquentInterface;
use App\Infrastructure\Database\Eloquent\ReportRepositoryEloquent;
use App\Infrastructure\Database\Eloquent\ReportStatusRepositoryEloquent;
use App\Infrastructure\Database\Eloquent\ReportTypeRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class EloquentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->bindBaseRepositoryClasses();
        $this->bindRepositoryClasses();
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
     * Bind base repository classes
     *
     * @return void
     */
    private function bindBaseRepositoryClasses(): void
    {
        $this->app->bind(RepositoryEloquentInterface::class, BaseRepositoryEloquent::class);
    }

    /**
     * Bind repository classes
     *
     * @return void
     */
    private function bindRepositoryClasses(): void
    {
        $this->app->bind(GatewayLogRepositoryInterface::class, GatewayLogRepositoryEloquent::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepositoryEloquent::class);
        $this->app->bind(ReportStatusRepositoryInterface::class, ReportStatusRepositoryEloquent::class);
        $this->app->bind(ReportTypeRepositoryInterface::class, ReportTypeRepositoryEloquent::class);
    }
}
