<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price_offer_bulletins extends Model
{
    protected $fillable = [
        'label',
        'nb_hours',
        'nb',
        'cost',
        'price_offer_id',
    ];

    use HasFactory;
}
