<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
        'address_ar',
        'address_en',
        'city',
        'vat',
        'email',
        'fax',
        'tel',
        'responsible',
        'mobile',
        'payment_method_id',
    ];

}
