<?php

namespace App\Providers;

use App\Services\FlureeService;
use Illuminate\Support\ServiceProvider;

class FlureeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FlureeService::class, function ($app) {
            return new FlureeService();
        });
    }

    public function boot(): void
    {
        //
    }
}