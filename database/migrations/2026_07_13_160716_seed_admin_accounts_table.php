<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $adminAccounts = [
            [
                'name' => 'Admin Success',
                'email' => 'admin@success.local',
                'password' => Hash::make('password'),
                'is_admin' => 1,
            ],
            [
                'name' => 'Admin Formation',
                'email' => 'admin@formation.pro',
                'password' => Hash::make('password'),
                'is_admin' => 1,
            ]
        ];

        foreach ($adminAccounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => $account['password'],
                    'is_admin' => $account['is_admin'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safety: Do not delete admin accounts on rollback to prevent accidental loss
    }
};
