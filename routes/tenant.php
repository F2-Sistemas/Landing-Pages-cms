<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/test-list', function () {
        $text = 'Tenant: ' . tenant('id') . '| Count users: ' . User::count() . '<br><br>';

        foreach (User::select(['id', 'name', 'tenant_id'])
            ->get() as $user) {
            $text .= "#{$user->id} - {$user->name} | {$user->tenant_id} <br>";
        }

        return $text;
    })->name('tenant.test_list');
});
