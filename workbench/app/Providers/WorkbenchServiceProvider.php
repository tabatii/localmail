<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Tabatii\LocalMail\Facades\LocalMail;

class WorkbenchServiceProvider extends ServiceProvider
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
        LocalMail::enableDevMode();
    }
}
