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
        // Update news table
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['image', 'image_mime_type']);
            $table->string('image_url')->nullable()->after('category');
            $table->string('image_public_id')->nullable()->after('image_url');
        });

        // Update projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['image', 'image_mime_type']);
            $table->string('image_url')->nullable()->after('location');
            $table->string('image_public_id')->nullable()->after('image_url');
        });

        // Update gallery table
        Schema::table('gallery', function (Blueprint $table) {
            $table->dropColumn(['media', 'media_mime_type', 'thumbnail', 'thumbnail_mime_type']);
            $table->string('media_url')->nullable()->after('type');
            $table->string('media_public_id')->nullable()->after('media_url');
            $table->string('thumbnail_url')->nullable()->after('media_public_id');
            $table->string('thumbnail_public_id')->nullable()->after('thumbnail_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse news table
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'image_public_id']);
            $table->longBlob('image')->nullable()->after('category');
            $table->string('image_mime_type')->nullable()->after('image');
        });

        // Reverse projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'image_public_id']);
            $table->longBlob('image')->nullable()->after('location');
            $table->string('image_mime_type')->nullable()->after('image');
        });

        // Reverse gallery table
        Schema::table('gallery', function (Blueprint $table) {
            $table->dropColumn(['media_url', 'media_public_id', 'thumbnail_url', 'thumbnail_public_id']);
            $table->longBlob('media')->nullable()->after('type');
            $table->string('media_mime_type')->nullable()->after('media');
            $table->longBlob('thumbnail')->nullable()->after('media_mime_type');
            $table->string('thumbnail_mime_type')->nullable()->after('thumbnail');
        });
    }
};
