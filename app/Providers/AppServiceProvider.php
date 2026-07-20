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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Blade::directive('fileurl', function ($expression) {
            return "<?php
                \$path = {$expression};
                echo (\$path && (str_starts_with(\$path, 'http://') || str_starts_with(\$path, 'https://')))
                    ? \$path
                    : asset('storage/' . (\$path ?? ''));
            ?>";
        });
    }
}
