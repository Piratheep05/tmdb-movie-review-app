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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('movie_id'); // TMDB movie ID
            $table->string('movie_title'); // Store movie title for quick access
            $table->text('review_text');
            $table->unsignedTinyInteger('rating')->nullable(); // 1-5 or 1-10 rating
            $table->timestamps();

            // Indexes for performance
            $table->index('user_id');
            $table->index('movie_id');
            $table->index(['user_id', 'movie_id']); // Composite index for unique user reviews per movie
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
