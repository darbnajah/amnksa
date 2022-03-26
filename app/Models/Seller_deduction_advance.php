<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller_deduction_advance extends Model
{
    use HasFactory;
    protected $fillable = [
        'dt',
        'label',
        'debit',
        'credit',
        'type',
        'seller_id',
        'payment_id',
    ];
}
