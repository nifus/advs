<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['system','info'])->default('system');
            $table->integer('user_id')->nullable();
            $table->string('header')->nullable();
            $table->string('name')->nullable();
            $table->text('body')->nullable();
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
        Schema::drop('mail_templates');
    }
}
