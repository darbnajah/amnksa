<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller_payment extends Model
{
    use HasFactory;
    protected  $table = 'sellers_payments';

    protected $fillable = [
        'dt',
        'month_id',
        'seller_id',
        'contract_id',
        'contract_obj',
        'city',
        'address',
        'amount',
        'advance',
        'deduction',
        'amount_net',
        'status',
        'accept_dt',
        'trans_status',
        'trans_dt',
        'deny_notes',

        'created_by',

    ];
}
