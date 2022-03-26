<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection_bulletins extends Model
{
    protected $fillable = [
        'label',
        'amount',
        'parent_id',
        'supplier_id',
    ];

}
