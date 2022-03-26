<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerDeductionAdvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_deduction_advances', function (Blueprint $table) {
            $table->id();
            $table->string('dt')->nullable();
            $table->string('label')->nullable();
            $table->string('debit')->nullable();
            $table->string('credit')->nullable();
            $table->string('type')->nullable();
            $table->string('seller_id')->nullable();
            $table->string('payment_id')->default(0);
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
        Schema::dropIfExists('seller_deduction_advances');
    }
}
