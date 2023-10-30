<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Throwable;

class TenantStorageController extends Controller
{
    public function getFromStorage($tenantId, $path = null)
    {
        return Tenant::findOrFail($tenantId)
                ?->run(function (Tenant $tenant) use ($path) {
                    $this->validatePath($path);

                    try {
                        return response()->file(storage_path("app/public/{$path}"));
                    } catch (Throwable $th) {
                        abort(404);
                    }
                });
    }

    public function abortIf($condition, string $exceptionMessage)
    {
        if (!$condition) {
            return;
        }

        abort(404);
    }

    public function validatePath(string|null $path): void
    {
        $this->abortIf($path === null, 'Empty path');

        $allowedRoot = realpath(storage_path('app/public'));

        // `storage_path('app/public')` doesn't exist, so it cannot contain files
        $this->abortIf($allowedRoot === false, "Storage root doesn't exist");

        $attemptedPath = realpath("{$allowedRoot}/{$path}");

        // User is attempting to access a nonexistent file
        $this->abortIf($attemptedPath === false, 'Accessing a nonexistent file');

        // User is attempting to access a file outside the $allowedRoot folder
        $this->abortIf(!str($attemptedPath)->startsWith($allowedRoot), 'Accessing a file outside the storage root');
    }
}
