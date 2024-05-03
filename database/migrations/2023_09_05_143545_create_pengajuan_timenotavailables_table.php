<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_timenotavailables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gurus_id')->unsigned()->nullable();
            $table->integer('days_id')->unsigned()->nullable();
            $table->integer('status')->nullable();
            $table->integer('times_id')->unsigned()->nullable();
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
        Schema::dropIfExists('pengajuan_timenotavailables');
    }
};
