<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    use BelongsToTenant;

    public function apply(Builder $builder, Model $model)
    {
        if (! tenancy()->initialized) {
            return;
        }

        $builder->where($model->qualifyColumn(static::$tenantIdColumn), tenant()->getTenantKey());
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withoutTenancy', fn (Builder $builder) => $builder->withoutGlobalScope($this));
    }
}
