<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction_advance extends Model
{
    use HasFactory;
    protected $fillable = [
        'dt',
        'label',
        'debit',
        'credit',
        'type',
        'employee_id',
        'salary_id',
    ];
}
