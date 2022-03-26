<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier_balance extends Model
{
    use HasFactory;
    protected $table = 'suppliers_balance';

    protected $fillable = [
        'doc_id',
        'doc_type',
        'number',
        'supplier_id',
        'contract_id',
        'parent_id',
        'dt',
        'label',
        'debit',
        'credit',
    ];
}
