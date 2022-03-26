<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->unique();
            $table->string('company_name_ar')->nullable();
            $table->string('company_name_en')->nullable();
            $table->string('address_ar')->nullable();
            $table->string('address_en')->nullable();

            $table->string('vat_number')->nullable();
            $table->string('license_number')->nullable();
            $table->string('commercial_record_date')->nullable();
            $table->string('license_date')->nullable();

            $table->string('notes')->nullable();

            $table->string('logo')->nullable();

             $table->string('cachet')->nullable();

             $table->string('sign_accountant_label')->nullable();
             $table->string('sign_operational_director_label')->nullable();
             $table->string('sign_financial_director_label')->nullable();
             $table->string('sign_price_offer_label')->nullable();

             $table->string('sign_accountant')->nullable();
             $table->string('sign_operational_director')->nullable();
             $table->string('sign_financial_director')->nullable();
             $table->string('sign_price_offer')->nullable();

            $table->string('company_db_name')->nullable()->unique();
            $table->string('company_db_user_first_name')->nullable();
            $table->string('company_db_user_last_name')->nullable();
            $table->string('company_db_user_email')->nullable()->unique();
            $table->string('company_db_user_name')->nullable();
            $table->string('company_db_user_password')->nullable();

            $table->integer('factor')->default(0);
            $table->string('expiration_dt')->nullable();


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
        Schema::dropIfExists('companies');
    }
}
