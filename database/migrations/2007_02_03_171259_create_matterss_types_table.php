<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatterssTypesTable extends Migration
{
   
    public function up()
    {
        Schema::create('matterss_types', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->timestamps();
        });
    
    }
    
    public function down()
    {
        Schema::dropIfExists('matterss_types');
    }
}
