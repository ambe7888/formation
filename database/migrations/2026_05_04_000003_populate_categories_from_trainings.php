<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories') || !Schema::hasTable('trainings')) {
            return;
        }

        $canonical = [
            'IA' => 'Intelligence artificielle',
            'Intelligence artificielle' => 'Intelligence artificielle',
            'Marketing' => 'Marketing',
            'Business' => 'Business',
            'Communication' => 'Communication',
            'Autres formations' => 'Autres formations',
        ];

        $defaultOrder = [
            'Intelligence artificielle' => 1,
            'Marketing' => 2,
            'Business' => 3,
            'Communication' => 4,
            'Autres formations' => 5,
        ];

        $existingCategories = DB::table('categories')->pluck('id', 'name')->toArray();
        $distinctCategories = DB::table('trainings')
            ->select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->pluck('category')
            ->toArray();

        $inserted = [];
        foreach ($distinctCategories as $categoryName) {
            $finalName = $canonical[$categoryName] ?? $categoryName;
            if (isset($existingCategories[$finalName]) || isset($inserted[$finalName])) {
                continue;
            }

            $sortOrder = $defaultOrder[$finalName] ?? (count($existingCategories) + count($inserted) + 1);
            $inserted[$finalName] = [
                'name' => $finalName,
                'slug' => Str::slug($finalName),
                'description' => null,
                'sort_order' => $sortOrder,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($inserted)) {
            DB::table('categories')->insert(array_values($inserted));
        }

        $categoryIds = DB::table('categories')->pluck('id', 'name')->toArray();
        foreach ($distinctCategories as $categoryName) {
            $finalName = $canonical[$categoryName] ?? $categoryName;
            if (!isset($categoryIds[$finalName])) {
                continue;
            }

            DB::table('trainings')
                ->where('category', $categoryName)
                ->update([
                    'category_id' => $categoryIds[$finalName],
                    'category' => $finalName,
                ]);
        }
    }

    public function down(): void
    {
        // No automatic rollback for migrated categories.
    }
};
