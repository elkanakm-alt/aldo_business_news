<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminPostController extends Controller
{
    // Liste des articles avec Recherche et Filtres
public function index(Request $request)
{
    $query = Post::with(['user', 'category']);

    // Recherche par titre (Insensible à la casse avec WHERE LIKE)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'LIKE', '%' . $search . '%')
              ->orWhere('content', 'LIKE', '%' . $search . '%'); // Cherche aussi dans le contenu !
        });
    }

    // Filtre par catégorie
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Récupération des données
    $posts = $query->latest()->paginate(8)->withQueryString();
    $categories = Category::orderBy('name')->get();

    return view('admin.posts.index', compact('posts', 'categories'));
}
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'required|image|max:2048',
        ]);

        $imagePath = $request->file('image')->store('posts', 'public');

        Post::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . uniqid(),
            'content'     => $request->content,
            'category_id' => $request->category_id,
            'user_id'     => auth()->id(),
            'image'       => $imagePath,
            'views'       => 0,
            'likes'       => 0,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Article créé avec succès');
    }

    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = [
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'content'     => $request->content,
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Article mis à jour');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Article supprimé');
    }
}