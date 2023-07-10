<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdates1020 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cart_rate')->nullable()->after('user_type');
            $table->string('orders_status')->nullable()->after('user_type');
            $table->longText('disabled_payment_methods')->nullable()->after('active');
        });

        Schema::table('user_balances', function (Blueprint $table) {
            $table->string('operation_type')->default('plus')->after('user_id')->comment('plus, minus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('updates_1020');
    }
}
