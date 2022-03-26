<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('doc_id')->nullable();
            $table->string('doc_type')->nullable();
            $table->string('dt')->nullable();
            $table->string('label')->nullable();
            $table->string('number')->nullable();
            $table->string('month_id')->nullable();
            $table->string('dt_from')->nullable();
            $table->string('dt_to')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('contract_id')->nullable();
            $table->string('seller_id')->nullable();
            $table->double('debit')->default(0);
            $table->double('credit')->default(0);
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
        Schema::dropIfExists('customer_expenses');
    }
}
