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
        return redirect()->route('admin.accounts.index')->with('open_create_modal', true);
    }

    /**
     * Enregistre un nouveau compte administrateur.
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_create_modal', true);
        }

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
        return redirect()->route('admin.accounts.index')->with('open_edit_modal', $account->id);
    }

    /**
     * Met à jour le compte administrateur.
     */
    public function update(Request $request, User $account)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($account->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit_modal', $account->id);
        }

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
