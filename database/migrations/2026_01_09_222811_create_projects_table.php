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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->enum('status', ['completed', 'ongoing', 'upcoming'])->default('ongoing');
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->binary('image')->nullable(); // Store image as binary
            $table->string('image_mime_type')->nullable();
            $table->string('impact')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
