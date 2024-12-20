<?php

namespace App\Infrastructure\Database\Eloquent\Providers;

use App\Infrastructure\Database\Eloquent\BaseRepositoryEloquent;
use App\Infrastructure\Database\Eloquent\Interfaces\RepositoryEloquentInterface;
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
}