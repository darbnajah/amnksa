<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'bank_name',
        'company_name_at_bank',
        'company_name_at_bank_en',
        'iban',
        'vat_number',
    ];
}
