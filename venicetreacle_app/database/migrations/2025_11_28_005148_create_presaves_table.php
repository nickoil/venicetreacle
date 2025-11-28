<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presaves', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_user_id')->index(); // User's Spotify ID
            $table->string('display_name')->nullable(); // Public display name
            $table->string('email')->nullable(); // Spotify email
            $table->json('profile_images')->nullable(); // Array of image URLs
            $table->string('country', 5)->nullable(); // User country
            $table->string('product')->nullable(); // free / premium / open
            $table->string('refresh_token'); // Token used on release day

            $table->string('track_id'); // Track this pre-save belongs to
            $table->string('state')->nullable(); // State value used to validate OAuth

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presaves');
    }
};
