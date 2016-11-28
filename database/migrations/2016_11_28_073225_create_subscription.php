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

        Schema::create('tariffs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('type_id');
            $table->enum('is_future',['0','1'])->default('0');
            $table->enum('is_paid',['0','1'])->default('0');
            $table->datetime('buy_time');
            $table->timestamps();
        });

        Schema::create('tariffs_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('tariff_id');
            $table->integer('adv_id');

            $table->enum('is_extra_slot',['0','1'])->default('0');
            $table->datetime('buy_time');
            $table->datetime('activate_time');
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
        Schema::drop('subscriptions');
        Schema::drop('subscriptions_details');
    }
}
