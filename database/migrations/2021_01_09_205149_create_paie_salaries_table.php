<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaieSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paie_salaries', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->nullable();
            $table->string('city')->nullable();
            $table->string('work_zone')->nullable();
            $table->string('salary')->nullable();
            $table->string('nb_days')->nullable();
            $table->string('advance')->nullable();
            $table->string('deduction')->nullable();
            $table->string('extra')->nullable();
            $table->string('salary_net')->nullable();
            $table->string('paie_id')->nullable();
            $table->string('status')->default(0);
            $table->string('accept_dt')->nullable();
            $table->string('trans_status')->default(0);
            $table->string('trans_dt')->nullable();
            $table->string('trans_notes')->nullable();
            $table->string('deny_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paie_salaries');
    }
}
