<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvsPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advs_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adv_id');
            $table->integer('tariff_id')->nullable();
            $table->enum('payment_type',['paypal','giro','prepayment'])->default('paypal');
            $table->enum('status',['wait','error','success'])->default('wait');
            $table->text('payment_log')->nullable();
            $table->string('guid')->nullable();
            $table->string('paypal_email')->nullable();
            $table->string('giro')->nullable();
            $table->decimal('price',8,2)->default(0);
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
        Schema::drop('advs_payments');
    }
}
