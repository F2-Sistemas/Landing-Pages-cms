<?php

namespace App\Tenancy\Bootstrappers;

use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;
use Illuminate\Support\Facades\Storage;

class TenantStorageDirectoryBootstrapper implements TenancyBootstrapper
{
    public function bootstrap(Tenant $tenant)
    {
        $tenantStorageDir = dirname(Storage::path(''));

        $dirs = [
            'framework',
            'framework/views',
            'framework/sessions',
            'framework/cache',
            'framework/testing',
            'app',
            'app/livewire-tmp',
            'app/public',
            'logs',
        ];

        foreach ($dirs as $dir) {
            $fullDirPath = "{$tenantStorageDir}/{$dir}";

            if (is_file($fullDirPath)) {
                continue; // WIP
            }

            if (!is_dir($fullDirPath)) {
                mkdir($fullDirPath, 0755, true);
            }
        }
    }

    public function revert()
    {
        // ...
    }
}
