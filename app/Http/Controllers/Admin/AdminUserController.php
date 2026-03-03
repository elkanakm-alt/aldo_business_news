<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Liste des utilisateurs avec RECHERCHE et pagination
     */
    public function index(Request $request)
    {
        // On récupère le terme de recherche
        $search = $request->input('search');

        $users = User::withCount('posts')
            ->when($search, function ($query, $search) {
                // Filtre si une recherche est présente
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(8)
            ->withQueryString(); // Garde le paramètre ?search=... dans les liens de pagination

        return view('admin.users.index', compact('users'));
    }

    /**
     * Formulaire création utilisateur
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Stockage nouvel utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'nullable', // On gère la conversion en booléen après
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->has('is_admin'), // true si coché, false sinon
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès ✨');
    }

    /**
     * Formulaire édition utilisateur
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mise à jour utilisateur
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'nullable',
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'is_admin' => $request->has('is_admin'), // Gère proprement le toggle/checkbox
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Le profil de ' . $user->name . ' a été mis à jour.');
    }

    /**
     * Suppression utilisateur (avec sécurité)
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Action impossible : vous ne pouvez pas vous auto-supprimer.');
        }

        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé définitivement.');
    }
}