<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller_balance extends Model
{
    use HasFactory;
    protected $table = 'sellers_balance';

    protected $fillable = [
        'doc_id',
        'doc_type',
        'number',
        'seller_id',
        'contract_id',
        'dt',
        'label',
        'debit',
        'credit',
    ];
}
