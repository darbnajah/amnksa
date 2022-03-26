<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price_offers extends Model
{
    use HasFactory;

    protected $fillable = [
      'accept_dt',
      'customer_name',
      'customer_dealer',
      'customer_dealer_mobile',
      'customer_dealer_email',
      'customer_tel',
      'customer_city',
      'customer_address',
      'status',
      'notes',
      'total',
      'model_id',
      'commercial_id',

    ];
}
