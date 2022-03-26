<?php

namespace Database\Seeders;

use App\Models\Payment_method;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Payment_method::create([
            'pm_name' => 'كاش'
        ]);
        Payment_method::create([
            'pm_name' => 'إيداع'
        ]);
    }
}
