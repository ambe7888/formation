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
        if (!Schema::hasTable('skills')) {
            Schema::create('skills', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->string('badge_color')->default('#4f46e5');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('skill_training')) {
            Schema::create('skill_training', function (Blueprint $table) {
                $table->id();
                $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
                $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_training');
        Schema::dropIfExists('skills');
    }
};
