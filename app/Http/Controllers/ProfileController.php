<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        // Retourne la vue d'édition du profil
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation du nom et de la photo
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $photoPath = $user->photo;

        // Gestion du téléchargement de la nouvelle photo
        if ($request->hasFile('photo')) {
            // Optionnel : supprimer l'ancienne photo pour ne pas encombrer le serveur
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            
            $photoPath = $request->file('photo')->store('authors', 'public');
        }

        // Mise à jour de l'utilisateur
        $user->update([
            'name' => $request->name,
            'photo' => $photoPath
        ]);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function destroy(Request $request)
    {
        // Méthode laissée vide ou à implémenter si besoin de supprimer le compte
    }
}