<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share settings to all views
        if (Schema::hasTable('settings')) {
            $settings = Setting::pluck('value', 'key')->toArray();
            View::share('globalSettings', $settings);
        }
    }
}
