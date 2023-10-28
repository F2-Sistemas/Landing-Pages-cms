<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Database\Eloquent\SoftDeletes;

class ContentTree extends Model
{
    use HasFactory;
    use SoftDeletes;

    // public $table = '';

    protected $fillable = [
        'nome',
        'uuid',
        'uuid_pai',
        'tree_id',
    ];

    public function tree(): HasOne
    {
        return $this->hasOne(Tree::class);
    }

    // Retorno
    public function auditFormLists(): HasMany
    {
        return $this->hasMany(AuditFormList::class, 'content_tree_id', 'id');
    }
}
