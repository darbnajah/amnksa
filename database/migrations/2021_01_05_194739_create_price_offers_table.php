<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_offers', function (Blueprint $table) {
            $table->id();
            $table->string('accept_dt')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_dealer')->nullable();
            $table->string('customer_dealer_mobile')->nullable();
            $table->string('customer_dealer_email')->nullable();
            $table->string('customer_tel')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('status')->nullable();
            $table->string('notes')->nullable();
            $table->string('total')->nullable();
            $table->string('model_id')->nullable();
            $table->string('commercial_id')->nullable();

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
        Schema::dropIfExists('price_offers');
    }
}
