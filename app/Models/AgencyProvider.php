<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyProvider extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'agency_providers';

    protected $fillable = [
        'provider_codigo',
    ];

    public static function onlyTenantScope(): bool
    {
        return true;
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_codigo', 'codigo');
    }
}
