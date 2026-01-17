<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gallery', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('title');
            $table->enum('type', ['image', 'video'])->default('image');
            $table->binary('media')->nullable(); // Store media as binary
            $table->string('media_mime_type')->nullable();
            $table->binary('thumbnail')->nullable(); // For video thumbnails
            $table->string('thumbnail_mime_type')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery');
    }
};
