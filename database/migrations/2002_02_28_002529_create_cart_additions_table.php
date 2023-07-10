<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartAdditionsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_additions', function (Blueprint $table) {

            $table->id();
            $table->integer('cartitem_id');
            $table->integer('addition_id');
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_additions');
    }
}
