<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Training;
use App\Models\Category;
use App\Models\Skill;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Bundle;
use App\Models\TrainingResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key constraints to safely truncate/delete tables
        Schema::disableForeignKeyConstraints();

        // 1. Clean existing registrations and payments
        Payment::truncate();
        Registration::truncate();

        // 2. Clean existing bundles
        foreach (Bundle::all() as $bundle) {
            $bundle->trainings()->detach();
            $bundle->delete();
        }

        // 3. Clean existing training-skill relations and resources
        TrainingResource::truncate();
        foreach (Training::all() as $t) {
            $t->skills()->detach();
            $t->delete();
        }

        // 4. Clean existing skills
        foreach (Skill::all() as $s) {
            $s->trainings()->detach();
            $s->delete();
        }

        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();

        // 5. Re-create or fetch default categories
        $catIA = Category::updateOrCreate(['id' => 1], ['name' => 'Intelligence artificielle', 'sort_order' => 1]);
        $catMarketing = Category::updateOrCreate(['id' => 2], ['name' => 'Marketing', 'sort_order' => 2]);
        $catBusiness = Category::updateOrCreate(['id' => 3], ['name' => 'Business', 'sort_order' => 3]);
        $catAutres = Category::updateOrCreate(['id' => 4], ['name' => 'Autres formations', 'sort_order' => 4]);

        // 6. Create relevant skills for the new trainings
        $skillsData = [
            ['name' => 'E-Commerce', 'badge_color' => '#f97316'],
            ['name' => 'Tunnel de Vente', 'badge_color' => '#a855f7'],
            ['name' => 'Copywriting', 'badge_color' => '#ec4899'],
            ['name' => 'Publicité Meta', 'badge_color' => '#3b82f6'],
            ['name' => 'Automatisation IA', 'badge_color' => '#10b981'],
            ['name' => 'ChatGPT / Claude', 'badge_color' => '#06b6d4'],
            ['name' => 'Make / Zapier', 'badge_color' => '#ef4444'],
            ['name' => 'Visibilité Digitale', 'badge_color' => '#14b8a6'],
        ];

        $skills = [];
        foreach ($skillsData as $s) {
            $skills[$s['name']] = Skill::create([
                'name' => $s['name'],
                'slug' => Str::slug($s['name']),
                'badge_color' => $s['badge_color']
            ]);
        }

        // 7. Create the 4 new formations from the document
        
        // Formation 1: E-commerce (Juin)
        $t1 = Training::create([
            'title' => 'E-COMMERCE : Créer votre boutique en ligne rentable',
            'category_id' => $catBusiness->id,
            'category' => $catBusiness->name,
            'description' => 'Conçu pour obtenir les bases solides afin de construire un système de vente en ligne complet et maîtriser l\'entrepreneuriat digital.',
            'start_date' => '2026-06-10',
            'planned_month' => 'Juin',
            'location' => 'En ligne (Zoom)',
            'price' => 50000,
            'promo_price' => 0,
            'seats' => 30,
            'is_active' => true,
            'is_featured' => true,
            'hero_order' => 1,
            'image_url' => 'assets/images/ecommerce.svg',
        ]);
        $t1->skills()->attach([$skills['E-Commerce']->id, $skills['Visibilité Digitale']->id]);

        // Formation 2: Tunnel de Vente (Juin)
        $t2 = Training::create([
            'title' => 'TUNNEL DE VENTE : Construire un système de vente automatique',
            'category_id' => $catMarketing->id,
            'category' => $catMarketing->name,
            'description' => 'Construire un système de vente automatique pas à pas pour capturer des prospects qualifiés et les convertir en clients en continu.',
            'start_date' => '2026-06-20',
            'planned_month' => 'Juin',
            'location' => 'En ligne (Zoom)',
            'price' => 50000,
            'promo_price' => 0,
            'seats' => 30,
            'is_active' => true,
            'is_featured' => true,
            'hero_order' => 2,
            'image_url' => 'assets/images/sales-funnel.svg',
        ]);
        $t2->skills()->attach([$skills['Tunnel de Vente']->id, $skills['Copywriting']->id]);

        // Formation 3: Visibilité (Juillet)
        $t3 = Training::create([
            'title' => 'VISIBILITE : Comment attirer des clients et générer des ventes rapidement',
            'category_id' => $catMarketing->id,
            'category' => $catMarketing->name,
            'description' => 'Apprenez à attirer des clients qualifiés et à générer des ventes rapidement en maîtrisant les canaux publicitaires et stratégies digitales.',
            'start_date' => '2026-07-10',
            'planned_month' => 'Juillet',
            'location' => 'En ligne (Zoom)',
            'price' => 50000,
            'promo_price' => 0,
            'seats' => 30,
            'is_active' => true,
            'is_featured' => true,
            'hero_order' => 3,
            'image_url' => 'assets/images/visibility.svg',
        ]);
        $t3->skills()->attach([$skills['Visibilité Digitale']->id, $skills['Publicité Meta']->id]);

        // Formation 4: IA (Juillet)
        $t4 = Training::create([
            'title' => 'AUTOMATISATION IA : Utiliser l’IA dans votre business',
            'category_id' => $catIA->id,
            'category' => $catIA->name,
            'description' => 'Gagnez du temps et augmentez vos résultats en intégrant l\'intelligence artificielle (ChatGPT, Claude) et les outils d\'automatisation (Make, Zapier) dans votre activité.',
            'start_date' => '2026-07-20',
            'planned_month' => 'Juillet',
            'location' => 'En ligne (Zoom)',
            'price' => 50000,
            'promo_price' => 0,
            'seats' => 30,
            'is_active' => true,
            'is_featured' => true,
            'hero_order' => 4,
            'image_url' => 'assets/images/ai-automation.svg',
        ]);
        $t4->skills()->attach([$skills['Automatisation IA']->id, $skills['ChatGPT / Claude']->id, $skills['Make / Zapier']->id]);

        // 8. Create the Packages (Combinations of Packs)
        
        // Pack 1: Pack Starter Pro (100 000 FCFA)
        $b1 = Bundle::create([
            'name' => 'Pack Starter Pro',
            'price' => 100000,
            'description' => 'Conçu pour obtenir les bases solides afin de construire un système de vente en ligne complet et maîtriser l\'entrepreneuriat digital. Comprend les 4 formations du programme, 2 séances de coaching sur Zoom, l\'accès à la communauté privée d\'entrepreneurs et le support technique.',
            'is_featured' => true,
            'hero_order' => 1,
        ]);
        $b1->trainings()->attach([$t1->id, $t2->id, $t3->id, $t4->id]);

        // Pack 2: Pack Suivi Elite (200 000 FCFA)
        $b2 = Bundle::create([
            'name' => 'Pack Suivi Elite',
            'price' => 200000,
            'description' => 'Pour un accompagnement individuel avancé afin de garantir des résultats concrets. Comprend l\'accès complet au Pack Starter Pro, 1 séance de coaching personnalisé par semaine sur Zoom ou en présentiel, et un bonus exclusif de 2 semaines de suivi après la formation.',
            'is_featured' => true,
            'hero_order' => 2,
        ]);
        $b2->trainings()->attach([$t1->id, $t2->id, $t3->id, $t4->id]);

        // Pack 3: Pack Performance Plus (600 000 FCFA)
        $b3 = Bundle::create([
            'name' => 'Pack Performance Plus',
            'price' => 600000,
            'description' => 'L\'offre haut de gamme pour un business clé en main avec suivi. Comprend le Pack Suivi Elite, une boutique en ligne ou un tunnel de vente clé en main construit par notre équipe, 2 mois de coaching personnalisé et un bonus de 1 mois de gestion.',
            'is_featured' => true,
            'hero_order' => 3,
        ]);
        $b3->trainings()->attach([$t1->id, $t2->id, $t3->id, $t4->id]);
    }
}
