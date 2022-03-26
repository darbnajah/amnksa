<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'doc_id',
        'doc_type',
        'dt',
        'label',
        'number',
        'month_id',
        'dt_from',
        'dt_to',
        'customer_id',
        'contract_id',
        'seller_id',
        'debit',
        'credit',
        'created_by',

    ];
}
