<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Training;
use App\Models\Registration;
use App\Models\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();

        $trainings = Training::with(['category', 'skills'])
            ->where('is_active', true)
            ->orderBy('start_date')
            ->get()
            ->map(function ($training) {
                $categoryName = is_object($training->category) ? ($training->category->name ?? '') : $training->category;
                $imageUrl = $training->image_url;
                if ($imageUrl) {
                    $illustration = asset($imageUrl);
                    if (!str_contains($imageUrl, 'assets/') && !file_exists(public_path($imageUrl))) {
                        $illustration = asset('storage/' . ltrim($imageUrl, '/'));
                    }
                } else {
                    $illustration = asset('assets/images/default-training.svg');
                }

                return [
                    'id' => $training->id,
                    'name' => $training->title,
                    'price' => $training->price,
                    'promo_price' => $training->promo_price ?: 0,
                    'tag' => $categoryName,
                    'group' => $categoryName,
                    'location' => $training->location ?: '',
                    'date' => $this->formatDateFr($training->start_date->format('Y-m-d')),
                    'planned_month' => $training->planned_month ?: 'Juin',
                    'available' => $training->seats,
                    'description' => $training->description,
                    'illustration' => $illustration,
                    'skills' => $training->skills,
                ];
            });

        $heroTrainings = Training::with(['category', 'skills'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('hero_order')
            ->get()
            ->map(function ($training) {
                $categoryName = is_object($training->category) ? ($training->category->name ?? '') : $training->category;
                $imageUrl = $training->image_url;
                if ($imageUrl) {
                    $illustration = asset($imageUrl);
                    if (!str_contains($imageUrl, 'assets/') && !file_exists(public_path($imageUrl))) {
                        $illustration = asset('storage/' . ltrim($imageUrl, '/'));
                    }
                } else {
                    $illustration = asset('assets/images/default-training.svg');
                }

                return [
                    'id'          => $training->id,
                    'name'        => $training->title,
                    'price'       => $training->price,
                    'promo_price' => $training->promo_price ?: 0,
                    'tag'         => $categoryName,
                    'group'       => $categoryName,
                    'location'    => $training->location ?: '',
                    'date'        => $this->formatDateFr($training->start_date->format('Y-m-d')),
                    'planned_month' => $training->planned_month ?: 'Juin',
                    'available'   => $training->seats,
                    'description' => $training->description,
                    'illustration'=> $illustration,
                    'skills'      => $training->skills,
                    'type'        => 'training',
                ];
            });

        // ── Featured bundles in hero ────────────────────────────────
        $heroBundles = \App\Models\Bundle::with(['trainings.category', 'trainings.skills'])
            ->where('is_featured', true)
            ->orderBy('hero_order')
            ->get()
            ->map(function ($bundle) {
                $totalPromoPrice = $bundle->trainings->sum(fn($t) => $t->promo_price ?: $t->price);
                $savings = max(0, $totalPromoPrice - $bundle->price);

                $imageUrl = $bundle->image_url;
                if (!$imageUrl) {
                    $firstTraining = $bundle->trainings->first();
                    $imageUrl = $firstTraining?->image_url;
                }

                if ($imageUrl) {
                    $illustration = asset($imageUrl);
                    if (!str_contains($imageUrl, 'assets/') && !file_exists(public_path($imageUrl))) {
                        $illustration = asset('storage/' . ltrim($imageUrl, '/'));
                    }
                } else {
                    $illustration = asset('assets/images/default-training.svg');
                }

                $trainingNames = $bundle->trainings->pluck('title')->take(3)->implode(', ');
                $formationsCount = $bundle->trainings->count() . ' formations';

                return [
                    'id'              => $bundle->id,
                    'name'            => $bundle->name,
                    'price'           => $bundle->price,
                    'promo_price'     => 0,
                    'tag'             => 'Pack Promotionnel',
                    'group'           => 'Packs',
                    'location'        => '',
                    'date'            => $formationsCount,
                    'available'       => $bundle->trainings->count(),
                    'description'     => $bundle->description,
                    'illustration'    => $illustration,
                    'skills'          => collect(),
                    'type'            => 'bundle',
                    'savings'         => $savings,
                    'total_original'  => $totalPromoPrice,
                    'trainings_count' => $bundle->trainings->count(),
                ];
            });

        // Merge featured trainings + featured bundles
        $heroTrainings = $heroTrainings->concat($heroBundles);

        $bundles = \App\Models\Bundle::with(['trainings.category', 'trainings.skills'])->get()
            ->map(function ($bundle) {
                $formattedTrainings = $bundle->trainings->map(function ($training) {
                    $categoryName = is_object($training->category) ? ($training->category->name ?? '') : $training->category;
                    $imageUrl = $training->image_url;
                    if ($imageUrl) {
                        $illustration = asset($imageUrl);
                        if (!str_contains($imageUrl, 'assets/') && !file_exists(public_path($imageUrl))) {
                            $illustration = asset('storage/' . ltrim($imageUrl, '/'));
                        }
                    } else {
                        $illustration = asset('assets/images/default-training.svg');
                    }

                    return [
                        'id' => $training->id,
                        'name' => $training->title,
                        'price' => $training->price,
                        'promo_price' => $training->promo_price ?: 0,
                        'tag' => $categoryName,
                        'location' => $training->location ?: '',
                        'date' => $this->formatDateFr($training->start_date->format('Y-m-d')),
                        'available' => $training->seats,
                        'description' => $training->description,
                        'illustration' => $illustration,
                        'skills' => $training->skills,
                    ];
                });

                $totalPromoPrice = $bundle->trainings->sum(function ($t) {
                    return $t->promo_price ?: $t->price;
                });
                $savings = max(0, $totalPromoPrice - $bundle->price);

                $imageUrl = $bundle->image_url;
                if (!$imageUrl) {
                    $firstTraining = $bundle->trainings->first();
                    $imageUrl = $firstTraining?->image_url;
                }

                if ($imageUrl) {
                    $illustration = asset($imageUrl);
                    if (!str_contains($imageUrl, 'assets/') && !file_exists(public_path($imageUrl))) {
                        $illustration = asset('storage/' . ltrim($imageUrl, '/'));
                    }
                } else {
                    $illustration = asset('assets/images/default-training.svg');
                }

                return [
                    'id' => $bundle->id,
                    'name' => $bundle->name,
                    'price' => $bundle->price,
                    'description' => $bundle->description,
                    'illustration' => $illustration,
                    'trainings' => $formattedTrainings,
                    'total_original' => $totalPromoPrice,
                    'savings' => $savings,
                ];
            });

        $trainingGroups = $this->buildTrainingGroups($categories, $trainings);

        return view('home', compact('trainings', 'trainingGroups', 'heroTrainings', 'bundles'));
    }

    public function trainingsPage()
    {
        $categories = Category::orderBy('sort_order')->get();
        $trainings = Training::with(['category', 'skills'])
            ->where('is_active', true)
            ->orderBy('start_date')
            ->get()
            ->map(function ($training) {
                $categoryName = is_object($training->category) ? ($training->category->name ?? '') : $training->category;
                $imageUrl = $training->image_url;
                if ($imageUrl) {
                    $illustration = asset($imageUrl);
                    if (!str_contains($imageUrl, 'assets/') && !file_exists(public_path($imageUrl))) {
                        $illustration = asset('storage/' . ltrim($imageUrl, '/'));
                    }
                } else {
                    $illustration = asset('assets/images/default-training.svg');
                }

                return [
                    'id' => $training->id,
                    'name' => $training->title,
                    'price' => $training->price,
                    'promo_price' => $training->promo_price ?: 0,
                    'tag' => $categoryName,
                    'group' => $categoryName,
                    'location' => $training->location ?: '',
                    'date' => $this->formatDateFr($training->start_date->format('Y-m-d')),
                    'planned_month' => $training->planned_month ?: 'Juin',
                    'available' => $training->seats,
                    'description' => $training->description,
                    'illustration' => $illustration,
                    'skills' => $training->skills,
                ];
            });

        $trainingGroups = $this->buildTrainingGroups($categories, $trainings);

        return view('trainings_page', compact('trainings', 'trainingGroups'));
    }

    public function programPage()
    {
        $trainings = Training::with(['category', 'skills'])
            ->where('is_active', true)
            ->orderBy('start_date')
            ->get()
            ->map(function ($training) {
                $categoryName = is_object($training->category) ? ($training->category->name ?? '') : $training->category;
                $imageUrl = $training->image_url;
                if ($imageUrl) {
                    $illustration = asset($imageUrl);
                    if (!str_contains($imageUrl, 'assets/') && !file_exists(public_path($imageUrl))) {
                        $illustration = asset('storage/' . ltrim($imageUrl, '/'));
                    }
                } else {
                    $illustration = asset('assets/images/default-training.svg');
                }

                return [
                    'id' => $training->id,
                    'name' => $training->title,
                    'price' => $training->price,
                    'promo_price' => $training->promo_price ?: 0,
                    'tag' => $categoryName,
                    'location' => $training->location ?: '',
                    'date' => $this->formatDateFr($training->start_date->format('Y-m-d')),
                    'available' => $training->seats,
                    'description' => $training->description,
                    'illustration' => $illustration,
                    'skills' => $training->skills,
                    'raw_date' => $training->start_date,
                ];
            });

        return view('program_page', compact('trainings'));
    }

    public function skillsPage()
    {
        $skills = \App\Models\Skill::with(['trainings' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        return view('skills_page', compact('skills'));
    }

    public function showTraining(Training $training)
    {
        $training->load(['category', 'skills', 'bundles.trainings']);
        $categoryName = is_object($training->category) ? ($training->category->name ?? '') : $training->category;
        
        $imageUrl = $training->image_url;
        if ($imageUrl) {
            $illustration = asset($imageUrl);
            if (!str_contains($imageUrl, 'assets/') && !file_exists(public_path($imageUrl))) {
                $illustration = asset('storage/' . ltrim($imageUrl, '/'));
            }
        } else {
            $illustration = asset('assets/images/default-training.svg');
        }

        $formattedTraining = [
            'id' => $training->id,
            'name' => $training->title,
            'price' => $training->price,
            'promo_price' => $training->promo_price ?: 0,
            'tag' => $categoryName,
            'group' => $categoryName,
            'location' => $training->location ?: '',
            'date' => $this->formatDateFr($training->start_date->format('Y-m-d')),
            'planned_month' => $training->planned_month ?: 'Juin',
            'available' => $training->seats,
            'description' => $training->description,
            'illustration' => $illustration,
            'skills' => $training->skills,
        ];

        return view('training_details', compact('formattedTraining', 'training'));
    }

    public function showBundle(\App\Models\Bundle $bundle)
    {
        $bundle->load(['trainings.category', 'trainings.skills']);
        
        $totalOriginalPrice = $bundle->trainings->sum(function ($training) {
            return $training->price;
        });

        $totalPromoPrice = $bundle->trainings->sum(function ($training) {
            return $training->promo_price ?: $training->price;
        });

        $savings = max(0, $totalPromoPrice - $bundle->price);

        // Format trainings inside bundle
        $formattedTrainings = $bundle->trainings->map(function ($training) {
            $categoryName = is_object($training->category) ? ($training->category->name ?? '') : $training->category;
            $imageUrl = $training->image_url;
            if ($imageUrl) {
                $illustration = asset($imageUrl);
                if (!str_contains($imageUrl, 'assets/') && !file_exists(public_path($imageUrl))) {
                    $illustration = asset('storage/' . ltrim($imageUrl, '/'));
                }
            } else {
                $illustration = asset('assets/images/default-training.svg');
            }

            return [
                'id' => $training->id,
                'name' => $training->title,
                'price' => $training->price,
                'promo_price' => $training->promo_price ?: 0,
                'tag' => $categoryName,
                'location' => $training->location ?: '',
                'date' => $this->formatDateFr($training->start_date->format('Y-m-d')),
                'available' => $training->seats,
                'description' => $training->description,
                'illustration' => $illustration,
                'skills' => $training->skills,
            ];
        });

        return view('bundle_details', compact('bundle', 'formattedTrainings', 'totalOriginalPrice', 'totalPromoPrice', 'savings'));
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'course' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        if (auth('client')->check()) {
            $client = auth('client')->user();
        } else {
            $client = Client::firstOrCreate(
                ['email' => $request->input('email')],
                [
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                ]
            );
        }

        // Intercept Bundle Enrolment
        if ($request->has('bundle_id') && !empty($request->input('bundle_id'))) {
            $bundle = \App\Models\Bundle::findOrFail($request->input('bundle_id'));
            
            // Check for duplicate registration
            $exists = Registration::where('client_id', $client->id)
                ->where('bundle_id', $bundle->id)
                ->where('status', '!=', 'canceled')
                ->exists();
                
            if ($exists) {
                if ($request->has('redirect_to')) {
                    return redirect($request->input('redirect_to') . '?status=duplicate&course=' . urlencode($bundle->name));
                }
                return redirect(url('/').'?status=duplicate&course=' . urlencode($bundle->name) . '#inscription');
            }

            Registration::create([
                'training_id' => null,
                'client_id' => $client->id,
                'seats' => 1,
                'amount' => $bundle->price,
                'status' => 'pending',
                'bundle_id' => $bundle->id,
                'notes' => json_encode([
                    'month' => $request->input('month'),
                    'message' => $request->input('message'),
                    'course' => $bundle->name,
                    'bundle_name' => $bundle->name,
                ], JSON_UNESCAPED_UNICODE),
            ]);
            
            if ($request->has('redirect_to')) {
                return redirect($request->input('redirect_to') . '?status=success&course=' . urlencode($bundle->name));
            }
            return redirect()->route('student.dashboard')->with('success', 'Votre inscription au pack ' . $bundle->name . ' a été enregistrée avec succès !');
        }

        // Standard individual training registration
        $training = Training::firstOrCreate(
            ['title' => $request->input('course')],
            [
                'category' => 'Autres formations',
                'description' => 'Formation ajoutée automatiquement pour sauvegarder l’inscription.',
                'start_date' => now()->format('Y-m-d'),
                'location' => '',
                'price' => 0,
                'promo_price' => 0,
                'seats' => 0,
                'is_active' => false,
            ]
        );

        // Check for duplicate registration
        $exists = Registration::where('client_id', $client->id)
            ->where('training_id', $training->id)
            ->where('status', '!=', 'canceled')
            ->exists();
            
        if ($exists) {
            if ($request->has('redirect_to')) {
                return redirect($request->input('redirect_to') . '?status=duplicate&course=' . urlencode($training->title));
            }
            return redirect(url('/').'?status=duplicate&course=' . urlencode($training->title) . '#inscription');
        }

        $price = $training->promo_price ?: $training->price;
        
        // Multi-training bundle discount (5,000 CFA off for 2nd or more training)
        $existingRegistrationsCount = Registration::where('client_id', $client->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
            
        $discountApplied = false;
        if ($existingRegistrationsCount > 0) {
            $price = max(0, $price - 5000);
            $discountApplied = true;
        }

        Registration::create([
            'training_id' => $training->id,
            'client_id' => $client->id,
            'seats' => 1,
            'amount' => $price,
            'status' => 'pending',
            'notes' => json_encode([
                'month' => $request->input('month'),
                'message' => $request->input('message'),
                'course' => $request->input('course'),
                'discount_applied' => $discountApplied ? '5000 CFA Multi-Formation' : 'None',
            ], JSON_UNESCAPED_UNICODE),
        ]);

        if ($request->has('redirect_to')) {
            $redirectUrl = $request->input('redirect_to') . '?status=success&course=' . urlencode($request->course);
            if ($discountApplied) {
                $redirectUrl .= '&discount=true';
            }
            return redirect($redirectUrl);
        }

        $successMsg = 'Votre inscription à la formation ' . $request->input('course') . ' a été enregistrée avec succès !';
        if ($discountApplied) {
            $successMsg .= ' 🎁 Offre Multi-Formation appliquée (réduction de 5 000 CFA).';
        }
        return redirect()->route('student.dashboard')->with('success', $successMsg);
    }

    private function formatDateFr(string $date): string
    {
        $parsed = \DateTime::createFromFormat('Y-m-d', $date);
        if (!$parsed) {
            return $date;
        }

        $months = [
            'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
            'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre',
        ];
        $monthIndex = (int) $parsed->format('n') - 1;
        $monthName = $months[$monthIndex] ?? $parsed->format('m');

        return $parsed->format('j') . ' ' . $monthName . ' ' . $parsed->format('Y');
    }

    private function buildTrainingGroups($categories, $trainings): array
    {
        $groups = [];
        foreach ($categories as $category) {
            $groupTrainings = $trainings->filter(fn($training) => $training['group'] === $category->name)->values();
            if ($groupTrainings->isEmpty()) {
                continue;
            }
            $groups[] = [
                'key' => $category->name,
                'title' => $category->name,
                'description' => $category->description ?: 'Formations disponibles dans cette catégorie.',
            ];
        }

        return $groups;
    }
}