<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdvAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advs_address', function (Blueprint $table) {
            $table->tinyInteger('display_house')->default(1);
            $table->text('distances');
            $table->integer('living_area');
            $table->string('floor')->nullable();
            $table->smallInteger('number_of_rooms');
            $table->string('number_of_garage')->nullable();
            $table->string('flat_type')->nullable();
        });

        Schema::table('advs', function (Blueprint $table) {

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
