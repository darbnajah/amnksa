<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create([
            'company_id' => 1,
            'company_name_ar' => 'الشامل للحراسات الأمنية',
            'company_db_name' => 'amnksa',
            'company_db_user_name' => 'root',
            'company_db_user_first_name' => 'مدلول',
            'company_db_user_last_name' => 'الشمري',
            'company_db_user_email' => 'mdloul@gmail.com',
            'company_db_user_password' => '123456',
        ]);

    }
}
