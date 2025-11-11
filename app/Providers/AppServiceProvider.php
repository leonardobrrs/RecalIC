<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\Ocorrencia;
use App\Observers\OcorrenciaObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Ocorrencia::observe(OcorrenciaObserver::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
