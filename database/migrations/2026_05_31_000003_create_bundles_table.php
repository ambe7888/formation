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
        if (!Schema::hasTable('bundles')) {
            Schema::create('bundles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->integer('price')->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('bundle_training')) {
            Schema::create('bundle_training', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bundle_id')->constrained('bundles')->onDelete('cascade');
                $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_training');
        Schema::dropIfExists('bundles');
    }
};
