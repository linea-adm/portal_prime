<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;


class AppServiceProvider extends AuthServiceProvider
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
    public function boot()
    {
        
        //  Comentar essa linha no ambiente de desenvolvimento
        \Illuminate\Support\Facades\URL::forceScheme('https');
        $this->registerPolicies();

        // Defina suas políticas e gates, se necessário

        Sanctum::ignoreMigrations();
    }

}
