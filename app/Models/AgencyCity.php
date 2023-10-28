<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyCity extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'agency_cities';

    protected $fillable = [
        'city_codigo',
    ];

    public static function onlyTenantScope(): bool
    {
        return true;
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_codigo', 'codigo');
    }
}
