<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price_offers_models extends Model
{
    use HasFactory;
    protected $fillable = [
        'model_name',
        'model_text',
        'is_default'
    ];
}
