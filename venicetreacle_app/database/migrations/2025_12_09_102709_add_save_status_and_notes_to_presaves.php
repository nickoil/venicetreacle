<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presaves', function (Blueprint $table) {
            $table->timestamp('saved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('presaves', function (Blueprint $table) {
            $table->dropColumn(['saved_at']);
        });
    }
};