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
        if (!Schema::hasTable('clients')) {
            Schema::create('clients', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('password')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        } else {
            Schema::table('clients', function (Blueprint $table) {
                if (!Schema::hasColumn('clients', 'password')) {
                    $table->string('password')->nullable()->after('email');
                }
                if (!Schema::hasColumn('clients', 'remember_token')) {
                    $table->rememberToken()->after('password');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (Schema::hasColumn('clients', 'remember_token')) {
                    $table->dropColumn('remember_token');
                }
                if (Schema::hasColumn('clients', 'password')) {
                    $table->dropColumn('password');
                }
            });
        }
    }
};
