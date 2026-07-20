<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!function_exists('file_url')) {
            function file_url(?string $path): string
            {
                if ($path && (str_starts_with($path, 'http://') || str_starts_with($path, 'https://'))) {
                    return $path;
                }

                return asset('storage/' . ($path ?? ''));
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
