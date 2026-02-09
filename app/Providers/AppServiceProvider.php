<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\WebsiteSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Kosongkan, jangan taruh logic berat di sini
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();


        $defaultSettings = new WebsiteSetting([
            'nama' => 'Website',
            'logo' => asset('default-image/default_logo.png'),
        ]);


        View::composer('*', function ($view) use ($defaultSettings) {
            $settings = $defaultSettings;

            if (Schema::hasTable('website_settings')) {
                $settings = WebsiteSetting::first() ?? $defaultSettings;
            }

            $view->with('settings', $settings);
        });
    }
}
