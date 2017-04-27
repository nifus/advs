<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarifs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tariffs');
        Schema::dropIfExists('tariffs_details');

        Schema::create('business_tariffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->smallInteger('number_of_slots')->default(0);
            $table->decimal('price')->default(0);
            $table->decimal('price_extra_slots',8,2)->default(0);
            //$table->integer('user_id')->default(null)->nullable();
          //  $table->timestamps();
        });

        Schema::create('private_tariffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('duration');
            $table->decimal('rent_price',8,2)->default(0);
            $table->decimal('sale_price',8,2)->default(0);
           // $table->integer('user_id')->default(null)->nullable();
           // $table->timestamps();
        });

        Schema::create('users_tariff', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('tariff_id');
            $table->enum('is_future',['0','1'])->default('0');
            $table->enum('is_paid',['0','1'])->default('0');
            $table->enum('is_end',['0','1'])->default('0');
            $table->datetime('begin_time');
            $table->datetime('end_time');
            $table->decimal('price',8,2);
            $table->decimal('extra',8,2);
            $table->smallInteger('slots');


            $table->timestamps();
        });

        Schema::create('users_tariff_slots', function (Blueprint $table) {
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
        Schema::drop('business_tariffs');
        Schema::drop('private_tariffs');
        Schema::drop('users_tariff');
        Schema::drop('users_tariff_slots');
    }
}
