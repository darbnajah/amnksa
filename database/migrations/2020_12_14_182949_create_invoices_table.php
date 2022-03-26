<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('contract_id')->nullable();
            $table->string('year_id')->nullable();
            $table->string('month_id')->nullable();
            $table->string('month_days')->nullable();
            $table->string('nb_days')->nullable();
            $table->string('customer_code')->nullable();
            $table->string('contract_code')->nullable();
            $table->string('invoice_code')->nullable();
            $table->string('dt')->nullable();
            $table->string('dt_from')->nullable();
            $table->string('dt_to')->nullable();
            $table->double('vat')->default(0);
            $table->string('vat_due_dt')->nullable();
            $table->double('total_vat')->default(0);
            $table->string('discount_subject')->nullable();
            $table->double('discount_value')->default(0);
            $table->double('ht')->default(0);
            $table->double('ttc')->default(0);

            $table->string('vat_status')->default(0);
            $table->string('vat_pay_ref')->nullable();
            $table->string('vat_pay_dt')->nullable();

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
        Schema::dropIfExists('invoices');
    }
}
