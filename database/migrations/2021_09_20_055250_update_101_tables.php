<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Update101Tables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->integer('otp_code')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->longText('description')->nullable();
        });

        Schema::table('customer_shipping_addresses', function (Blueprint $table) {
            $table->boolean('set_default')->default(0);
        });

        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('position')->default('top');
            $table->boolean('status')->default(0);
            $table->timestamps();

        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('message');
            $table->json('options')->nullable();
            $table->integer('times')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();

        });

        Schema::create('notification_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('notification_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('times')->default(0);
            $table->timestamps();

//            $table->foreign('notification_id')->references('id')->on('notifications');
//            $table->foreign('user_id')->references('id')->on('users');

        });

        Schema::create('notification_subscribers', function (Blueprint $table) {
            $table->id();
            $table->text('player_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

//            $table->foreign('user_id')->references('id')->on('users');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
