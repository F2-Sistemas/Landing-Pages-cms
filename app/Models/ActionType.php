<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActionType extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'action_types';

    protected $fillable = [
        'action_type',
    ];
}
