<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paie extends Model
{
    use HasFactory;
    protected $fillable = [
        'month_id',
        'nb_days',
        'month_days',
        'paie_dt',

        'created_by',
    ];
}
