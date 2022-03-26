<?php

namespace Database\Seeders;

use App\Models\Paper;
use Illuminate\Database\Seeder;

class PaperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Paper::create([
            'paper_name' => 'ورق افتراضي',
            'header_img' => 'img/companies/papers/default_paper_header.jpg',
            'footer_img' => 'img/companies/papers/default_paper_footer.jpg',
            'company_id' => 1,
            'is_default' => 1
        ]);
    }
}
