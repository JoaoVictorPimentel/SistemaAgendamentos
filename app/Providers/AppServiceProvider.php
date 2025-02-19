<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Policies\AgendamentoPolicy;
use App\Models\Agendamento;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['auth']->viaRequest('api', function ($request) {
        });

        \Gate::policy(Agendamento::class, AgendamentoPolicy::class);
        Vite::prefetch(concurrency: 3);
    }
}
