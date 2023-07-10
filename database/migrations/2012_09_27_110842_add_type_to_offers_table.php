<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToOffersTable extends Migration
{
   
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('type');
            $table->integer('percent');

        });
    }

    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            //
        });
    }
}
