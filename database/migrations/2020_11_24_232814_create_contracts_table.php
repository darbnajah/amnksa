<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('dt_start')->nullable();
            $table->string('dt_end')->nullable();
            $table->string('status')->default(1);
            $table->string('seller_id')->nullable();
            $table->double('contract_total')->default(0);
            $table->string('seller_commission')->nullable();

            $table->string('supplier_id')->nullable();
            $table->string('supplier_commission')->nullable();

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
        Schema::dropIfExists('contracts');
    }
}
