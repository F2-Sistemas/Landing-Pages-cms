<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'reports';

    protected $fillable = [
        'titulo',
        'conteudo',
        'config',
    ];

    protected $casts = [
        'conteudo' => 'array',
        'config' => 'array',
    ];

    public static function onlyTenantScope(): bool
    {
        return true;
    }
}
