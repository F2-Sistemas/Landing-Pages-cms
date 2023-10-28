<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Tenant
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'public.tenants';

    public static function onlyGlobalScope(): bool
    {
        // Legenda:
        // true:  Isso diz que essa model (a resource identifica isso) só permite ser acessada se tiver FORA do contexto de tenant
        // false: Pode ser acessada com ou sem contexto de tenant
        return true;
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_codigo', 'codigo');
    }
}
