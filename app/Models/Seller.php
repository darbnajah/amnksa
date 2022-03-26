<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_1',
        'mobile_2',
        'payment_method_id',
        'bank_name',
        'bank_iban',
        'bank_account',

        'can_login',

        'email',
        'password_visible',

        'created_by',

    ];

}
