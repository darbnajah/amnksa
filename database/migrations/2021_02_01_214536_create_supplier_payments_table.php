<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers_payments', function (Blueprint $table) {
            $table->id();
            $table->string('dt')->nullable();
            $table->string('month_id')->nullable();
            $table->string('supplier_id')->nullable();
            $table->string('contract_id')->nullable();
            $table->json('contract_obj')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('supplier_amount')->nullable();

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
        Schema::dropIfExists('suppliers_payments');
    }
}
