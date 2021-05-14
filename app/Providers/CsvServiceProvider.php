<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Provider for CSV Service
 */
class CsvServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap service.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('csvService', 'App\Services\CsvService');
    }
}
