<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

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
        if (app()->isProduction() || config('app.force_https')) {
            ($this->{'app'}['request'] ?? null)?->server?->set('HTTPS', 'on');
            URL::forceScheme('https');
        }

        $this->themeNamespaces();
    }

    public function themeNamespaces()
    {
        $themesPaths = [
            'tail-single' => resource_path('views/themes/tail-single'),
        ];

        foreach ($themesPaths as $themeNamespace => $themePath) {
            View::addNamespace($themeNamespace, $themePath);
        }
    }
}
