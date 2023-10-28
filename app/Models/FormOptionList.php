<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormOptionList extends Model
{
    use HasFactory;
    use SoftDeletes;

    // public $table = '';

    protected $fillable = [
        'form_option_id',
        'opcao',
        'conforme',
        'ordem',
    ];

    public function formOption(): HasOne
    {
        return $this->hasOne(FormOption::class, 'id', 'form_option_id');
    }
}
