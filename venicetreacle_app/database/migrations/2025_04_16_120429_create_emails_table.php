<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->uuid('laravel_message_id')->unique();
            $table->string('email_address', 255)->nullable()->index();
            $table->string('subject', 255)->nullable();
            $table->text('body')->nullable();
            $table->string('sender', 255)->nullable();
            $table->timestamp('queued_time')->nullable();
            $table->timestamp('sent_time')->nullable();
            $table->timestamp('complete_time')->nullable();
            $table->string('service_message_id', 64)->unique()->nullable();
            $table->integer('email_status_id')->index();
            $table->text('notes')->nullable();
            $table->string('message_type', 64)->default('Unknown')->index();
            $table->timestamps(); 
        });

        Schema::table('emails', function (Blueprint $table) {
            $table->foreign('email_status_id')->references('id')->on('email_statuses');
        });
    }

    public function down()
    {
        Schema::dropIfExists('emails');
    }
}