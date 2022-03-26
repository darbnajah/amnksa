<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();

            $table->string('employee_name');

            $table->string('city')->nullable();
            $table->string('work_zone')->nullable();
            $table->string('dt_start')->nullable();
            $table->string('salary')->nullable();
            $table->string('mobile_1')->nullable();

            $table->string('civil_card_number')->nullable();
            $table->string('civil_card_issue')->nullable();
            $table->string('civil_card_expire_dt')->nullable();
            $table->string('attach_civil_card')->nullable();

            $table->string('bank_account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('attach_bank')->nullable();

            $table->string('job_id')->nullable();

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
        Schema::dropIfExists('employees');
    }
}
