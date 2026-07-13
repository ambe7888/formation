<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
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
     * Affiche la liste des comptes administrateurs.
     */
    public function index()
    {
        $admins = User::orderBy('name')->get();
        return view('admin.accounts.index', compact('admins'));
    }

    /**
     * Affiche le formulaire de création d'un compte admin.
     */
    public function create()
    {
        return view('admin.accounts.create');
    }

    /**
     * Enregistre un nouveau compte administrateur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Compte administrateur créé avec succès.');
    }

    /**
     * Affiche le formulaire de modification d'un compte admin.
     */
    public function edit(User $account)
    {
        return view('admin.accounts.edit', compact('account'));
    }

    /**
     * Met à jour le compte administrateur.
     */
    public function update(Request $request, User $account)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($account->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $account->update($data);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Compte administrateur mis à jour avec succès.');
    }

    /**
     * Supprime le compte administrateur.
     */
    public function destroy(User $account)
    {
        // Empêcher l'administrateur connecté de se supprimer lui-même
        if (auth()->id() === $account->id) {
            return redirect()->route('admin.accounts.index')
                ->withErrors(['erreur' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $account->delete();

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Compte administrateur supprimé.');
    }
}
