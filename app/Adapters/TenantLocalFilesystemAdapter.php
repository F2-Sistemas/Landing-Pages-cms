<?php

namespace App\Adapters;

use Illuminate\Support\Str;
use Illuminate\Filesystem\FilesystemAdapter as FilesystemFilesystemAdapter;
use Illuminate\Contracts\Filesystem\Cloud as CloudFilesystemContract;

class TenantLocalFilesystemAdapter extends FilesystemFilesystemAdapter implements CloudFilesystemContract
{
    /**
     * Get the URL for the file at the given path.
     *
     * @param  string  $path
     * @return string
     */
    protected function getLocalUrl($path)
    {
        $configUrl = $this->config['url'] ?? '';
        $parsedConfigUrl = parse_url($configUrl) ?: [];
        $parsedAppAssetUrl = parse_url(app('url')->asset('')) ?: [];

        $parsedNewUrl = array_merge(
            $parsedConfigUrl,
            $parsedAppAssetUrl,
        );

        $host = $parsedNewUrl['host'] ?? $parsedAppAssetUrl['host'];

        if ($tenant = tenant()) {
            $host = $tenant?->mainDomain(true);
        }

        $port = $parsedNewUrl['port'] ?? '';
        $configUrl = sprintf('%s://%s%s%s', ...[
            $parsedNewUrl['scheme'] ?? 'http',
            $host,
            ($port && $port != 80) ? ":{$port}" : '',
            $parsedConfigUrl['path'] ?? '',
        ]);

        // If an explicit base URL has been set on the disk configuration then we will use
        // it as the base URL instead of the default path. This allows the developer to
        // have full control over the base path for this filesystem's generated URLs.
        if (isset($configUrl)) {
            return $this->concatPathToUrl($configUrl, $path);
        }

        $path = '/storage/' . $path;

        // If the path contains "storage/public", it probably means the developer is using
        // the default disk to generate the path instead of the "public" disk like they
        // are really supposed to use. We will remove the public from this path here.
        if (str_contains($path, '/storage/public/')) {
            return Str::replaceFirst('/public/', '/', $path);
        }

        return $path;
    }
}
