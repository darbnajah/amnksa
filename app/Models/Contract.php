<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_id',
        'city',
        'address',
        'dt_start',
        'dt_end',
        'status',
        'seller_id',
        'contract_total',
        'seller_commission',

        'supplier_id',
        'supplier_commission',

    ];

}
