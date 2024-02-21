<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class ClienteAuthProvider extends ServiceProvider
{
    protected $policies = [
        // Policies, se necessário
    ];

    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('cliente', function ($app, array $config) {
            return new ClienteAuthProvider($app->make('App\Models\Cliente'));
        });
    }
}
