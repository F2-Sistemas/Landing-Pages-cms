<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tree extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'trees';

    protected $fillable = [
        'nome',
        'provider_codigo',
        'city_codigo',
        'service_id',
        'content_tree',
    ];

    protected $casts = [
        'content_tree' => 'array',
    ];

    public static function onlyTenantScope(): bool
    {
        return true;
    }

    public function agencyProvider(): HasOne
    {
        return $this->hasOne(AgencyProvider::class, 'codigo', 'provider_codigo');
    }

    public function agencyCity(): HasOne
    {
        return $this->hasOne(AgencyCity::class, 'codigo', 'city_codigo');
    }

    public function sevice(): HasOne
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public function providerName(): HasOne
    {
        return $this->hasOne(Provider::class, 'codigo', 'provider_codigo');
    }

    public function cityName(): HasOne
    {
        return $this->hasOne(City::class, 'codigo', 'city_codigo');
    }

    public function contentTrees(): HasMany
    {
        return $this->hasMany(ContentTree::class);
    }

    // Retorno
    public function auditFormLists(): HasMany
    {
        return $this->hasMany(AuditFormList::class, 'tree_id', 'id');
    }
}
