<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers_payments', function (Blueprint $table) {
            $table->id();
            $table->string('dt')->nullable();
            $table->string('month_id')->nullable();
            $table->string('seller_id')->nullable();
            $table->string('contract_id')->nullable();
            $table->json('contract_obj')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('amount')->nullable();
            $table->string('advance')->nullable();
            $table->string('deduction')->nullable();
            $table->string('amount_net')->nullable();
            $table->string('status')->default(0);
            $table->string('accept_dt')->nullable();
            $table->string('trans_status')->default(0);
            $table->string('trans_dt')->nullable();
            $table->string('deny_notes')->nullable();

            $table->string('created_by')->nullable();

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
        Schema::dropIfExists('seller_payments');
    }
}
