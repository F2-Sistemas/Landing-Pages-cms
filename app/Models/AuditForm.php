<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditForm extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'audit_forms';

    protected $fillable = [
        'titulo',
        'descricao',
        'conteudo',
    ];

    protected $casts = [
        'conteudo' => 'array',
    ];

    public static function onlyTenantScope(): bool
    {
        return true;
    }

    // Retorno
    public function auditFormLists(): HasMany
    {
        return $this->hasMany(AuditFormList::class, 'audit_form_id', 'id');
    }
}
