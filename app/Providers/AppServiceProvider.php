<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LogCadastroService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LogCadastroService::class, function ($app) {
            return new LogCadastroService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
