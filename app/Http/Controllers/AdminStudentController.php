<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminStudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check()) {
                return redirect()->route('admin.login');
            }

            return $next($request);
        });
    }

    /**
     * Affiche la liste des comptes étudiants.
     */
    public function index()
    {
        $students = Client::orderBy('name')->get();
        return view('admin.students.index', compact('students'));
    }

    /**
     * Affiche le formulaire de création d'un étudiant.
     */
    public function create()
    {
        return redirect()->route('admin.students.index')->with('open_create_modal', true);
    }

    /**
     * Enregistre un nouvel étudiant.
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'phone' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_create_modal', true);
        }

        Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Compte étudiant créé avec succès.');
    }

    /**
     * Affiche le formulaire de modification d'un étudiant.
     */
    public function edit(Client $student)
    {
        return redirect()->route('admin.students.index')->with('open_edit_modal', $student->id);
    }

    /**
     * Met à jour le compte étudiant.
     */
    public function update(Request $request, Client $student)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('clients')->ignore($student->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit_modal', $student->id);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $student->update($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Compte étudiant mis à jour avec succès.');
    }

    /**
     * Supprime le compte étudiant.
     */
    public function destroy(Client $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Compte étudiant supprimé.');
    }
}
