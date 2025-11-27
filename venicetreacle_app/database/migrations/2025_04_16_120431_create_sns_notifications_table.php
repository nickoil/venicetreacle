<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSnsNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('sns_notifications', function (Blueprint $table) {
            $table->id();
            $table->timestamp('received_time');
            $table->text('headers');
            $table->text('request');
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('sns_notifications');
    }
}