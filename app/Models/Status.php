<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'cor',
        'icone',
    ];

    public function plans(): HasMany
    {
        return $this->hasMany(AuditFormList::class, 'status_id', 'id');
    }
}
