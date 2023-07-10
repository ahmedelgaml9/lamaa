<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTimesTable extends Migration
{
   
    public function up()
    {
        Schema::create('order_times', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_times');
    }
}
