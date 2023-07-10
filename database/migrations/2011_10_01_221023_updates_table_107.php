<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatesTable107 extends Migration
{

    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {

            $table->integer('group_price')->nullable();
            $table->text('description')->nullable();

        });

        Schema::table('promocodes', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->integer('product_type')->nullable();
            $table->integer('amount')->nullable();
            $table->text('description')->nullable();

        });
    }


    public function down()
    {
        //
    }
}
