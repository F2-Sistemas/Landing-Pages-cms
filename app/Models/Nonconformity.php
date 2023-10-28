<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nonconformity extends Model
{
    use HasFactory;
    use SoftDeletes;

    // public $table = '';

    protected $fillable = [
        'audit_form_list_id',
        'pergunta_uuid',
        'pergunta',
        'recomendacao',
        'referencia',
        'prazo',
    ];

    public function auditFormList(): HasOne
    {
        return $this->hasOne(AuditFormList::class);
    }
}
