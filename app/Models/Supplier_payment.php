<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier_payment extends Model
{
    use HasFactory;
    protected  $table = 'suppliers_payments';

    protected $fillable = [
        'dt',
        'month_id',
        'supplier_id',
        'contract_id',
        'contract_obj',
        'city',
        'address',
        'supplier_amount',
    ];

}
