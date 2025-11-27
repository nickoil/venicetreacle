<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlackListedEmailAddressesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('blacklisted_email_addresses', function (Blueprint $table) {
            $table->id(); 
            $table->string('email_address'); 
            $table->timestamp('excluded_time'); 
            $table->string('service_message_id', 64); 
            $table->integer('email_status_id'); 
            $table->timestamps(); 
        });

        Schema::table('blacklisted_email_addresses', function (Blueprint $table) {
            $table->foreign('email_status_id')->references('id')->on('email_statuses');
        });

        Schema::table('blacklisted_email_addresses', function (Blueprint $table) {
            $table->foreign('service_message_id')->references('service_message_id')->on('emails');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blacklisted_email_addresses');
    }
};
