<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('subscription_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->smallInteger('number_of_slots')->default(0);
            $table->decimal('price')->default(0);
            $table->decimal('price_extra_slots')->default(0);
            $table->timestamps();
        });*/

        Schema::create('user_tariffs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('tariff_id');
            $table->enum('is_future',['0','1'])->default('0');
            $table->enum('is_paid',['0','1'])->default('0');
            $table->datetime('begin_time');
            $table->datetime('end_time');
            $table->decimal('price',8,2);
            $table->decimal('extra',8,2);
            $table->smallInteger('slots');


            $table->timestamps();
        });

        Schema::create('user_tariffs_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('tariff_id');
            $table->integer('adv_id')->nullable();

            $table->enum('is_extra_slot',['0','1'])->default('0');
            $table->datetime('buy_time')->nullable();
            $table->datetime('activate_time')->nullable();
            $table->enum('is_paid',['0','1'])->default('0');

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
        Schema::drop('tariffs');
        Schema::drop('tariffs_details');
    }
}
