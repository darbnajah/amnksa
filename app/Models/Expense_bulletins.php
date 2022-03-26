<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense_bulletins extends Model
{
    protected $fillable = [
        'label',
        'amount',
        'parent_id',
    ];

}
