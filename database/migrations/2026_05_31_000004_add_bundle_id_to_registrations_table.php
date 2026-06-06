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
        if (Schema::hasTable('registrations')) {
            Schema::table('registrations', function (Blueprint $table) {
                if (!Schema::hasColumn('registrations', 'bundle_id')) {
                    $table->foreignId('bundle_id')->nullable()->constrained('bundles')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('registrations')) {
            Schema::table('registrations', function (Blueprint $table) {
                if (Schema::hasColumn('registrations', 'bundle_id')) {
                    $table->dropForeign(['bundle_id']);
                    $table->dropColumn('bundle_id');
                }
            });
        }
    }
};
