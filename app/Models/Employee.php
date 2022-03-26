<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'code',
        'employee_name',

        'city',
        'work_zone',
        'dt_start',
        'salary',
        'mobile_1',

        'civil_card_number',
        'civil_card_issue',
        'civil_card_expire_dt',
        'attach_civil_card',

        'bank_account_name',
        'bank_name',
        'bank_iban',
        'bank_account',
        'attach_bank',

        'job_id',
    ];
    use HasFactory;

}
