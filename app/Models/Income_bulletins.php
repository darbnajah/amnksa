<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income_bulletins extends Model
{
    protected $fillable = [
        'label',
        'amount',
        'parent_id',
    ];

}
