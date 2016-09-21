<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBusinessFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('is_approved')->default(0);
            $table->string('company')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('giro_account')->nullable();
            $table->enum('payment_type',['paypal','giropay','prepyment'])->default('paypal');
            $table->string('paypal_email')->nullable();
            $table->string('phone')->nullable();
            $table->smallInteger('tariff')->nullable();
            $table->string('website')->nullable();
            $table->string('commercial_country')->nullable();
            $table->string('commercial_id')->nullable();
            $table->string('commercial_additional')->nullable();
            $table->string('address_additional')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_zip')->nullable();


        });
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
