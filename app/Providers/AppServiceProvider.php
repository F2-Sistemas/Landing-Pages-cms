<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->tenantLocalStorage();
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

    public function tenantLocalStorage()
    {
        Storage::extend('local_tenant', function ($app, $config) {
            $localStorage = Storage::disk('local');
            $localAdapter = $localStorage?->getAdapter();
            $localDriver = $localStorage?->getDriver();

            return new \App\Adapters\TenantLocalFilesystemAdapter(
                driver: $localDriver,
                adapter: $localAdapter,
                config: $config,
            );
        });
    }
}
