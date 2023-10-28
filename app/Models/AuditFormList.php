<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditFormList extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'audit_form_lists';

    protected $fillable = [
        'titulo',
        'tree_id',
        'content_tree_id',
        'audit_form_id',
        'data_inicio',
        'data_termino',
        'respondido',
    ];

    // public function tree():HasOne {
    //     return $this->hasOne(Tree::class, 'id', 'tree_id');
    // }

    public static function onlyTenantScope(): bool
    {
        return true;
    }

    public function tree(): HasOne
    {
        return $this->hasOne(Tree::class, 'id', 'tree_id');
    }

    public function contentTree(): HasOne
    {
        return $this->hasOne(ContentTree::class, 'id', 'content_tree_id');
    }

    public function auditForm(): HasOne
    {
        return $this->hasOne(AuditForm::class, 'id', 'audit_form_id');
    }

    // Retorno
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'audit_form_list_id', 'id');
    }

    public function nonconformities(): HasMany
    {
        return $this->hasMany(Nonconformity::class, 'audit_form_list_id', 'id');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class, 'audit_form_list_id', 'id');
    }
}
