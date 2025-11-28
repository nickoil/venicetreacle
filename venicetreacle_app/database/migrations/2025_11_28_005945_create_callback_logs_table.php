<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('callback_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service'); // e.g., 'spotify'
            $table->string('state')->nullable();
            $table->text('code')->nullable();
            $table->text('error')->nullable();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->boolean('success')->default(false);
            $table->integer('status')->nullable();
            $table->text('body')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            
            $table->index(['service', 'created_at']);
            $table->index('success');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('callback_logs');
    }
};