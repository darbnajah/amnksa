<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paie_salaries extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'city',
        'work_zone',
        'salary',
        'nb_days',
        'advance',
        'deduction',
        'extra',
        'salary_net',
        'paie_id',
        'status',
        'accept_dt',
        'trans_status',
        'trans_dt',
        'trans_notes',
        'deny_notes',
    ];
}
