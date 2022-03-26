<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers_balance', function (Blueprint $table) {
            $table->id();
            $table->string('doc_id')->nullable();
            $table->string('doc_type')->nullable();
            $table->string('number')->nullable();
            $table->string('seller_id')->nullable();
            $table->string('contract_id')->nullable();
            $table->string('dt')->nullable();
            $table->json('label')->nullable();
            $table->double('debit')->default(0);
            $table->double('credit')->default(0);
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
        Schema::dropIfExists('sellers_balance');
    }
}
