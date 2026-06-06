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
        if (!Schema::hasTable('trainings')) {
            Schema::create('trainings', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('category');
                $table->text('description');
                $table->date('start_date');
                $table->string('location')->nullable();
                $table->integer('price');
                $table->integer('promo_price')->nullable();
                $table->integer('seats');
                $table->string('image_url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};