<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'contract_id',
        'year_id',
        'month_id',
        'month_days',
        'nb_days',
        'customer_code',
        'contract_code',
        'invoice_code',
        'dt',
        'dt_from',
        'dt_to',
        'vat',
        'vat_due_dt',
        'total_vat',
        'discount_subject',
        'discount_value',
        'ht',
        'ttc',

        'status',
        'pay_ref',
        'pay_dt',

        'vat_status',
        'vat_pay_ref',
        'vat_pay_dt',
        'created_by',
    ];

    use HasFactory;
}
