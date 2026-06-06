<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Training;
use App\Models\Skill;
use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('admin_logged_in')) {
                return redirect()->route('admin.login');
            }

            return $next($request);
        });
    }

    public function dashboard()
    {
        $activeTrainings = Training::where('is_active', true)->count();
        $featuredTrainings = Training::where('is_active', true)->where('is_featured', true)->count();
        $registrations = Registration::count();
        $payments = Payment::count();
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.dashboard', compact('activeTrainings', 'featuredTrainings', 'registrations', 'payments', 'categories'));
    }

    public function trainings()
    {
        $trainings = Training::with('category')->orderByDesc('created_at')->get();
        return view('admin.trainings.index', compact('trainings'));
    }

    public function createTraining()
    {
        $categories = Category::orderBy('sort_order')->get();
        $skills = Skill::orderBy('name')->get();
        return view('admin.trainings.create', compact('categories', 'skills'));
    }

    public function storeTraining(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'planned_month' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'promo_price' => 'nullable|integer|min:0',
            'seats' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'hero_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $category = Category::find($request->input('category_id'));

        $data = $request->only(['title', 'description', 'start_date', 'planned_month', 'location', 'price', 'promo_price', 'seats', 'hero_order']);
        $data['category_id'] = $category->id;
        $data['category'] = $category->name;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->saveTrainingImage($request->file('image'));
        }

        $training = Training::create($data);
        $training->skills()->sync($request->input('skills', []));

        // Persist training resources
        $resourceTitles = $request->input('resource_title', []);
        $resourceTypes = $request->input('resource_type', []);
        $resourceUrls = $request->input('resource_url', []);
        $resourceDescriptions = $request->input('resource_description', []);

        foreach ($resourceTitles as $i => $title) {
            if (!empty($title) && !empty($resourceUrls[$i])) {
                $training->resources()->create([
                    'title' => $title,
                    'type' => $resourceTypes[$i] ?? 'link',
                    'url' => $resourceUrls[$i],
                    'description' => $resourceDescriptions[$i] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.trainings')->with('success', 'Formation créée avec succès.');
    }

    public function editTraining(Training $training)
    {
        $categories = Category::orderBy('sort_order')->get();
        $skills = Skill::orderBy('name')->get();
        return view('admin.trainings.edit', compact('training', 'categories', 'skills'));
    }

    public function updateTraining(Request $request, Training $training)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'planned_month' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'promo_price' => 'nullable|integer|min:0',
            'seats' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'hero_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $category = Category::find($request->input('category_id'));

        $data = $request->only(['title', 'description', 'start_date', 'planned_month', 'location', 'price', 'promo_price', 'seats', 'hero_order']);
        $data['category_id'] = $category->id;
        $data['category'] = $category->name;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->saveTrainingImage($request->file('image'));
        }

        $training->update($data);
        $training->skills()->sync($request->input('skills', []));

        // Persist training resources (flush and recreate)
        $training->resources()->delete();
        $resourceTitles = $request->input('resource_title', []);
        $resourceTypes = $request->input('resource_type', []);
        $resourceUrls = $request->input('resource_url', []);
        $resourceDescriptions = $request->input('resource_description', []);

        foreach ($resourceTitles as $i => $title) {
            if (!empty($title) && !empty($resourceUrls[$i])) {
                $training->resources()->create([
                    'title' => $title,
                    'type' => $resourceTypes[$i] ?? 'link',
                    'url' => $resourceUrls[$i],
                    'description' => $resourceDescriptions[$i] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.trainings')->with('success', 'Formation mise à jour avec succès.');
    }

    public function destroyTraining(Training $training)
    {
        $training->delete();

        return redirect()->route('admin.trainings')->with('success', 'Formation supprimée avec succès.');
    }

    public function categories()
    {
        $categories = Category::orderBy('sort_order')->withCount('trainings')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'slug', 'description', 'sort_order']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Category::create($data);

        return redirect()->route('admin.categories')->with('success', 'Catégorie créée avec succès.');
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'slug', 'description', 'sort_order']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Catégorie supprimée avec succès.');
    }

    public function moveCategoryUp(Category $category)
    {
        $previous = Category::where('sort_order', '<', $category->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($previous) {
            $currentOrder = $category->sort_order;
            $category->sort_order = $previous->sort_order;
            $previous->sort_order = $currentOrder;
            $category->save();
            $previous->save();
        }

        return redirect()->route('admin.categories');
    }

    public function moveCategoryDown(Category $category)
    {
        $next = Category::where('sort_order', '>', $category->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            $currentOrder = $category->sort_order;
            $category->sort_order = $next->sort_order;
            $next->sort_order = $currentOrder;
            $category->save();
            $next->save();
        }

        return redirect()->route('admin.categories');
    }

    public function registrations()
    {
        $activeRegistrations = Registration::with(['training', 'client', 'bundle', 'payments'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderByDesc('created_at')
            ->get();

        $canceledRegistrations = Registration::with(['training', 'client', 'bundle', 'payments'])
            ->where('status', 'canceled')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.registrations.index', compact('activeRegistrations', 'canceledRegistrations'));
    }

    public function payments()
    {
        $payments = Payment::with(['registration.training', 'registration.client'])->orderByDesc('created_at')->get();
        $registrations = Registration::with(['client', 'training'])->orderByDesc('created_at')->get();
        return view('admin.payments.index', compact('payments', 'registrations'));
    }

    public function updateRegistrationStatus(Request $request, Registration $registration)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        $status = $request->input('status');
        $notes = json_decode($registration->notes ?? '[]', true) ?: [];

        if ($status === 'canceled') {
            if (!isset($notes['canceled_at'])) {
                $notes['canceled_at'] = now()->toDateTimeString();
                $notes['canceled_by_admin'] = true;
            }
        } else {
            // If reactivating, clear cancellation info
            unset($notes['canceled_at']);
            unset($notes['canceled_by_student']);
            unset($notes['canceled_by_admin']);
        }

        $registration->update([
            'status' => $status,
            'notes' => json_encode($notes, JSON_UNESCAPED_UNICODE)
        ]);

        return redirect()->route('admin.registrations')->with('success', 'Statut de l\'inscription mis à jour avec succès.');
     }

     public function storePayment(Request $request)
     {
         $request->validate([
             'registration_id' => 'required|exists:registrations,id',
             'amount' => 'required|numeric|min:0',
             'method' => 'required|string|max:255',
             'status' => 'required|in:pending,completed,failed',
             'reference' => 'nullable|string|max:255',
         ]);

         Payment::create([
             'registration_id' => $request->input('registration_id'),
             'amount' => $request->input('amount'),
             'method' => $request->input('method'),
             'status' => $request->input('status'),
             'reference' => $request->input('reference'),
             'paid_at' => $request->input('status') === 'completed' ? now() : null,
         ]);

         return redirect()->route('admin.payments')->with('success', 'Paiement enregistré avec succès.');
     }

    public function skills()
    {
        $skills = Skill::withCount('trainings')->orderBy('name')->get();
        return view('admin.skills.index', compact('skills'));
    }

    public function storeSkill(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
            'slug' => 'nullable|string|max:255|unique:skills,slug',
            'badge_color' => 'required|string|max:7',
        ]);

        $data = $request->only(['name', 'slug', 'badge_color']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Skill::create($data);

        return redirect()->route('admin.skills')->with('success', 'Compétence créée avec succès.');
    }

    public function editSkill(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

    public function updateSkill(Request $request, Skill $skill)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name,' . $skill->id,
            'slug' => 'nullable|string|max:255|unique:skills,slug,' . $skill->id,
            'badge_color' => 'required|string|max:7',
        ]);

        $data = $request->only(['name', 'slug', 'badge_color']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $skill->update($data);

        return redirect()->route('admin.skills')->with('success', 'Compétence mise à jour avec succès.');
    }

    public function destroySkill(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.skills')->with('success', 'Compétence supprimée avec succès.');
    }

    public function bundles()
    {
        $bundles = Bundle::withCount('trainings')->orderBy('name')->get();
        return view('admin.bundles.index', compact('bundles'));
    }

    public function createBundle()
    {
        $trainings = Training::where('is_active', true)->orderBy('title')->get();
        return view('admin.bundles.create', compact('trainings'));
    }

    public function storeBundle(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:bundles,name',
            'price'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'hero_order'  => 'nullable|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'trainings'   => 'required|array|min:2',
            'trainings.*' => 'exists:trainings,id',
        ]);

        $data = $request->only(['name', 'price', 'description', 'hero_order']);
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->saveBundleImage($request->file('image'));
        }

        $bundle = Bundle::create($data);
        $bundle->trainings()->sync($request->input('trainings'));

        return redirect()->route('admin.bundles')->with('success', 'Pack promotionnel créé avec succès.');
    }

    public function editBundle(Bundle $bundle)
    {
        $trainings = Training::where('is_active', true)->orderBy('title')->get();
        return view('admin.bundles.edit', compact('bundle', 'trainings'));
    }

    public function updateBundle(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:bundles,name,' . $bundle->id,
            'price'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'hero_order'  => 'nullable|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'trainings'   => 'required|array|min:2',
            'trainings.*' => 'exists:trainings,id',
        ]);

        $data = $request->only(['name', 'price', 'description', 'hero_order']);
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->saveBundleImage($request->file('image'));
        }

        $bundle->update($data);
        $bundle->trainings()->sync($request->input('trainings'));

        return redirect()->route('admin.bundles')->with('success', 'Pack promotionnel mis à jour avec succès.');
    }

    public function destroyBundle(Bundle $bundle)
    {
        $bundle->delete();
        return redirect()->route('admin.bundles')->with('success', 'Pack promotionnel supprimé avec succès.');
    }

    protected function saveTrainingImage($file)
    {
        $destination = public_path('assets/images/trainings');
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
        $file->move($destination, $filename);

        return 'assets/images/trainings/' . $filename;
    }

    protected function saveBundleImage($file)
    {
        $destination = public_path('assets/images/bundles');
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
        $file->move($destination, $filename);

        return 'assets/images/bundles/' . $filename;
    }
}