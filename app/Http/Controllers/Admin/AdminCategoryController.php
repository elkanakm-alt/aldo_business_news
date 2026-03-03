<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    /**
     * Liste des catégories avec RECHERCHE et pagination.
     */
    public function index(Request $request)
    {
        // 1. On récupère le terme de recherche
        $search = $request->input('search');

        $categories = Category::withCount('posts')
            // 2. On applique le filtre si une recherche est présente
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('slug', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10) // Augmenté à 10 pour un meilleur rendu visuel
            ->withQueryString(); // 3. Indispensable pour garder la recherche lors du changement de page
            
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        Category::create([
            'name' => $validated['name'],
            // Génération automatique du slug si vide
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'La catégorie "' . $validated['name'] . '" a été créée avec succès ✨');
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Met à jour la catégorie existante.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès');
    }

    /**
     * Supprime la catégorie (avec sécurité posts).
     */
    public function destroy(Category $category)
    {
        // Sécurité : On empêche la suppression si la catégorie est utilisée
        if ($category->posts()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Action refusée : Cette catégorie est liée à ' . $category->posts()->count() . ' article(s).');
        }

        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée définitivement.');
    }
}