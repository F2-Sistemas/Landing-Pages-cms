<?php

namespace App\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;
    use HasDomains;
    use SoftDeletes;

    public $table = 'public.tenants';

    public static function onlyGlobalScope(): bool
    {
        // Legenda:
        // true:  Isso diz que essa model (a resource identifica isso) só permite ser acessada se tiver FORA do contexto de tenant
        // false: Pode ser acessada com ou sem contexto de tenant
        return true;
    }

    public function init(string|Tenant|null $tenant = null)
    {
        if ($tenant) {
            $tenant = is_string($tenant) ? static::findOrFail($tenant) : $tenant;
        }

        $tenant ??= $this;

        tenancy()->initialize($tenant);

        return tenant();
    }

    public static function end()
    {
        tenancy()->end();
    }

    public function initialize(string|Tenant|null $tenant = null)
    {
        return $this->init($tenant);
    }

    public static function initById(?string $tenantId)
    {
        if (! filled($tenantId)) {
            return null;
        }

        return app(static::class)->init($tenantId);
    }

    /**
     * Get an internal key.
     */
    public function getInternal(string $key)
    {
        return parent::getInternal($key);
    }
}
