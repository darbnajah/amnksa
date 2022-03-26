<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
            'company_id',
            'company_name_ar',
            'company_name_en',
            'address_ar',
            'address_en',
            'vat_number',
            'license_number',
            'commercial_record_date',
            'license_date',
            'notes',
            'logo',

            'cachet',

            'sign_accountant_label',
            'sign_operational_director_label',
            'sign_financial_director_label',
            'sign_price_offer_label',

            'sign_accountant',
            'sign_operational_director',
            'sign_financial_director',
            'sign_price_offer',

            'company_db_name',
            'company_db_user_first_name',
            'company_db_user_last_name',
            'company_db_user_email',
            'company_db_user_name',
            'company_db_user_password',

            'factor',

            'expiration_dt',
    ];
}
