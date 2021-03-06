<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdvFavs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advs', function (Blueprint $table) {
            $table->text('users_fav')->default(null)->nullable();
           // $table->integer('shop_window')->default(null)->nullable();
           // $table->string('development')->default(null)->nullable();
           // $table->string('building_permission')->default(null)->nullable();
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
