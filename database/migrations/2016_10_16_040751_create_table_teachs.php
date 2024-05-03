<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTeachs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachs', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('courses_id')->unsigned()->nullable();
            $table->integer('gurus_id')->unsigned()->nullable();
            $table->string('class_room');
            $table->string('year');
            $table->timestamps();

            $table->foreign('courses_id')->references('id')->on('courses')->OnUpdate('CASCADE')->OnDelete('CASCADE');
            $table->foreign('gurus_id')->references('id')->on('gurus')->OnUpdate('CASCADE')->OnDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teachs');
    }
}
