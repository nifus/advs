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
            $table->integer('user_id');
            $table->enum('status',['payment_waiting','active','disabled','expired','blocked'])->default('payment_waiting');
            $table->enum('type',['rent','sell','offer'])->default('rent');
            $table->text('photos')->nullable();
            $table->integer('visited')->default(0);
            $table->integer('favorite')->default(0);
            $table->date('move_date')->nullable();
            $table->enum('is_deleted',['0','1'])->default('0');
            $table->string('title');
            $table->string('category');
            $table->longText('desc')->nullable();
            $table->text('props')->nullable();
            $table->string('subcategory');
            $table->text('equipments')->nullable();
            $table->timestamps();



            $table->smallInteger('living_area')->nullable();
            $table->smallInteger('plot_area')->nullable();
            $table->smallInteger('area')->nullable();

            $table->smallInteger('floor')->nullable();
            $table->smallInteger('floors')->nullable();

            $table->smallInteger('rooms')->nullable();


            $table->double('cold_rent',15,2);
            $table->double('monthly_rent',15,2);
            $table->double('rental_price',15,2);
            $table->string('price_type')->nullable();
            $table->smallInteger('number_beds')->nullable();
            $table->double('storey_height')->nullable();




            $table->text('address')->nullable();
           // $table->text('distances')->nullable();
            $table->text('energy')->nullable();
            $table->text('author')->nullable();
            $table->text('finance')->nullable();
            $table->tinyInteger('hide_contacts')->default(0);

            $table->integer('city_id');
            $table->integer('country_id');
            $table->integer('region_id');


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
        Schema::drop('advs_fav');

    }
}
