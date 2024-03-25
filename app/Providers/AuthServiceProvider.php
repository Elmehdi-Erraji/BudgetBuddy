<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Depense;
use App\Policies\DepensePolicy;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerDepensePolicy();

    }

    public function registerDepensePolicy()
    {
        Gate::define('update-depense', [DepensePolicy::class, 'update']);
        Gate::define('delete-depense', [DepensePolicy::class, 'delete']);
    }
}
