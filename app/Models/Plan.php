<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory;
    use SoftDeletes;

    // public $table = '';

    protected $fillable = [
        'acao',
        'responsavel',
        'inicio_p',
        'termino_p',
        'inicio_r',
        'termino_r',
        'observacao',
        'etapas',
        'onde',
        'status_id',
        'action_type_id',
    ];

    public function auditFormList(): HasOne
    {
        return $this->hasOne(AuditFormList::class);
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    public function action_type(): HasOne
    {
        return $this->hasOne(ActionType::class, 'id', 'action_type_id');
    }
}
