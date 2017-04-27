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
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adv_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('tariff_id')->nullable();
            $table->smallInteger('slots')->nullable();
            $table->enum('type',['advert','subscription','slot'])->default('advert');
            $table->enum('way',['paypal','giro','prepayment'])->default('paypal');
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
        Schema::drop('payments');
    }
}
