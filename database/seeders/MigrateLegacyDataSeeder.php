<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Registration;

class MigrateLegacyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataFile = base_path('legacy/data/registrations.json');

        if (file_exists($dataFile)) {
            $existing = file_get_contents($dataFile);
            $decoded = json_decode($existing ?: '[]', true);
            if (is_array($decoded)) {
                foreach ($decoded as $entry) {
                    Registration::create([
                        'name' => $entry['name'] ?? '',
                        'phone' => $entry['phone'] ?? '',
                        'email' => $entry['email'] ?? '',
                        'course' => $entry['course'] ?? '',
                        'month' => $entry['month'] ?? '',
                        'message' => $entry['message'] ?? null,
                        'created_at' => $entry['created_at'] ?? now(),
                    ]);
                }
            }
        }
    }
}
