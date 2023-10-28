<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory;
    use SoftDeletes;

    // public $table = '';

    protected $fillable = [
        'audit_form_list_id',
        'user_id',
        'pergunta_uuid',
        'resposta',
        'resposta_type',
        'comentario',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function auditFormLists(): HasMany
    {
        return $this->hasMany(AuditFormList::class, 'id', 'audit_form_list_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}
