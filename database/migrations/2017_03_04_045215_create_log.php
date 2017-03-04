<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type',['system','mail','owner','initials'])->default('system');
            $table->integer('user_id')->nullable();
            $table->integer('adv_id')->nullable();
            $table->text('additional_fields')->nullable();
            $table->string('action');
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
        Schema::drop('events_log');
    }
}
