<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantIfHasOne
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = \Auth::user();

        if (! $user) {
            return $next($request);
        }

        if (! (tenancy()?->initialized) && ($tenantId = $user?->tenant_id ?? null)) {
            tenancy()?->initialize(\App\Models\Tenant::findOrFail($tenantId));
            abort_unless(tenancy()?->initialized, 404);
        }

        return $next($request);
    }
}
