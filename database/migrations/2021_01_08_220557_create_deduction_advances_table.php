<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionAdvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction_advances', function (Blueprint $table) {
            $table->id();
            $table->string('dt')->nullable();
            $table->string('label')->nullable();
            $table->string('debit')->nullable();
            $table->string('credit')->nullable();
            $table->string('type')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('salary_id')->default(0);
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
        Schema::dropIfExists('deduction_advances');
    }
}
