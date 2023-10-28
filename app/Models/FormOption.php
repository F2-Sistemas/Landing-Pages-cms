<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormOption extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'form_options';

    protected $fillable = [
        'nome',
    ];

    public static function onlyTenantScope(): bool
    {
        return true;
    }

    public function formOptionList(): HasMany
    {
        return $this->hasMany(FormOptionList::class);
    }
}
