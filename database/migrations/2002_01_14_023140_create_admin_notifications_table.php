<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminNotificationsTable extends Migration
{
    
    public function up()
    {
       
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('content');
            $table->integer('status');
            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('admin_notifications');
    }
}
