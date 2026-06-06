<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            if (!Schema::hasColumn('trainings', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('category');
            }

            if (!Schema::hasColumn('trainings', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }

            if (!Schema::hasColumn('trainings', 'hero_order')) {
                $table->integer('hero_order')->nullable()->after('is_featured');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            if (Schema::hasColumn('trainings', 'hero_order')) {
                $table->dropColumn('hero_order');
            }
            if (Schema::hasColumn('trainings', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            if (Schema::hasColumn('trainings', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });
    }
};
