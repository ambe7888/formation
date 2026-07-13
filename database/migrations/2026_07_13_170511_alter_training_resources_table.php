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
        // 1. Add Primary Key constraint to id column if not already set
        try {
            \DB::statement('ALTER TABLE training_resources ADD PRIMARY KEY (id);');
        } catch (\Exception $e) {
            // Primary key might already exist, ignore error
        }

        // 2. Enable AUTO_INCREMENT on the id column
        \DB::statement('ALTER TABLE training_resources MODIFY id BIGINT UNSIGNED AUTO_INCREMENT;');

        // 3. Expand type ENUM to include 'video'
        \DB::statement("ALTER TABLE training_resources MODIFY type ENUM('file', 'link', 'video') NOT NULL DEFAULT 'link';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert type to original enum
        \DB::statement("ALTER TABLE training_resources MODIFY type ENUM('file', 'link') NOT NULL DEFAULT 'link';");
    }
};
