<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBalancesTable extends Migration
{

    public function up()
    {
        Schema::create('user_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('order_id')->nullable();
            $table->string('reason_type')->nullable();
            $table->string('reason_note')->nullable();
            $table->float('value');
            $table->integer('status')->default(1);
            $table->timestamp('expiry_date')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_balances');
    }
}
