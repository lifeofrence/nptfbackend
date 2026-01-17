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
        Schema::table('testimonials', function (Blueprint $table) {
            // Add email column
            $table->string('email')->after('name');

            // Add organization column
            $table->string('organization')->nullable()->after('position');

            // Rename 'text' to 'content' for consistency
            $table->renameColumn('text', 'content');

            // Drop 'title' column as it's not needed
            $table->dropColumn('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Reverse the changes
            $table->dropColumn('email');
            $table->dropColumn('organization');
            $table->renameColumn('content', 'text');
            $table->string('title')->after('id');
        });
    }
};
