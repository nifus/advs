<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adv_id');
            $table->timestamps();
            $table->text('data');
            $table->string('ip');
            $table->enum('is_sent',['0','1']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('message_log');
    }
}
