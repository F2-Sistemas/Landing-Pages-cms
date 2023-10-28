<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $nome
 * @property int $codigo
 * @property string $uf
 * @property-read string $fullLabel
 */
class City extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'public.cities';

    protected $fillable = [
        'nome',
        'codigo',
        'uf',
    ];

    public static function onlyGlobalScope(): bool
    {
        // Legenda:
        // true:  Isso diz que essa model (a resource identifica isso) só permite ser acessada se tiver FORA do contexto de tenant
        // false: Pode ser acessada com ou sem contexto de tenant
        return true;
    }

    // Retorno
    public function agencies(): HasMany
    {
        return $this->hasMany(Agency::class, 'city_codigo', 'codigo');
    }

    public function agencyProviders(): HasMany
    {
        return $this->hasMany(AgencyProvider::class, 'provider_codigo', 'codigo');
    }

    public function getFullLabelAttribute()
    {
        return "{$this->uf} - {$this->nome}";
    }
}
