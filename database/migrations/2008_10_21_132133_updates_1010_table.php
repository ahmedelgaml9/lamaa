<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Updates1010Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('type')->default('single')->after('id');
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->integer('other_product_id')->unsigned()->nullable()->after('get');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->timestamp('send_at')->nullable();
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
