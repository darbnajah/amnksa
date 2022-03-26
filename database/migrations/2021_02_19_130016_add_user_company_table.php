<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('company_id')->unsigned();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on("companies");
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on("users");
            $table->unique("user_id");
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
