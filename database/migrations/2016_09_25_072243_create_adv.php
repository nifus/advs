<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->double('total_rent',15,2);
            $table->double('cold_rent',15,2);
            $table->double('ancillary_cost',15,2);
            $table->double('heating_cost',15,2);
            $table->double('caution_money',15,2);

            $table->integer('user_id');
            $table->enum('status',['payment_waiting','active','disabled','expired','blocked'])->default('payment_waiting');
            $table->enum('type',['rent','sell','offer'])->default('rent');
            $table->text('photos')->nullable();
            $table->integer('visited')->default(0);
            $table->integer('favorite')->default(0);
           // $table->integer('favorite')->default(0);
            $table->smallInteger('area')->nullable();
            $table->smallInteger('rooms')->nullable();
            $table->smallInteger('floor')->nullable();
            $table->smallInteger('floors')->nullable();
            $table->integer('address_id');
            $table->smallInteger('living_area')->nullable();
            $table->string('number_of_garage')->nullable();
            $table->enum('pets',['agreement'])->default('agreement');
            $table->date('move_date')->nullable();


            $table->longText('desc')->nullable();

            $table->timestamps();

        });

        Schema::create('advs_address', function (Blueprint $table) {
            $table->increments('id');

            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->enum('building_type',['flat'])->default('flat');
            $table->integer('building_year');
            $table->enum('heating',['central_heating'])->default('central_heating');
            $table->enum('garage',['underground_garage'])->default('underground_garage');
            /*$table->set('equipment',['Balcony / Terrace', 'New building', 'Built-in kitchen', 'Central heating',
                'Garden (-shared use)', 'Elevator Garage / parking space', 'Self-contained heating',
                'Stepless access', 'Guest toilet', 'Cellar'])->nullable();*/
            $table->timestamps();
        });

        Schema::create('advs_fav', function (Blueprint $table) {
            //$table->increments('id');
            $table->integer('user_id');
            $table->integer('adv_id');


           // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('advs');
        Schema::drop('advs_address');
        Schema::drop('advs_fav');

    }
}
