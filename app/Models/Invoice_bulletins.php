<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_bulletins extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'nb',
        'cost',
        'nb_days',
        'row_nb_days',
        'extra',
        'invoice_id',
    ];
}
