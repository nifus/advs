<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('profile')->default(0);
        });

        Schema::create('users_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->text('specializations')->nullable();
            $table->text('services')->nullable();
            $table->enum('company_data_source',['profile','settings'])->nullable('settings');
            $table->text('company_data')->nullable();
            $table->enum('person_data_source',['profile','settings'])->nullable('settings');
            $table->text('person_data')->nullable();
            $table->text('about')->nullable();
            $table->string('logo')->nullable();
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
        Schema::create('users_profile');
    }
}
