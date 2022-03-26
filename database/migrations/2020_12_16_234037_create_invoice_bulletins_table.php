<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceBulletinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_bulletins', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->double('nb')->default(0);
            $table->double('cost')->default(0);
            $table->string('nb_days')->nullable();
            $table->string('row_nb_days')->nullable();
            $table->integer('extra')->default(0);
            $table->integer('invoice_id');
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
        Schema::dropIfExists('invoice_bulletins');
    }
}
