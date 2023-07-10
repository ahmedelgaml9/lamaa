<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionsTable extends Migration
{
    
    public function up()
    {
        Schema::create('additions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->integer('service_id');
            $table->timestamps();
         });
    }

    public function down()
    {
        Schema::dropIfExists('additions');
    }
}
