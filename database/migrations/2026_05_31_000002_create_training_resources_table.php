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
        if (!Schema::hasTable('training_resources')) {
            Schema::create('training_resources', function (Blueprint $table) {
                $table->id();
                $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
                $table->string('title');
                $table->enum('type', ['file', 'link'])->default('link');
                $table->string('url', 500);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_resources');
    }
};
