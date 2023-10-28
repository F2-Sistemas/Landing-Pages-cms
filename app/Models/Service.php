<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'services';

    protected $fillable = [
        'nome',
    ];

    public static function onlyTenantScope(): bool
    {
        return true;
    }
}
