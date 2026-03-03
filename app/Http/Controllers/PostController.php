<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Page d'accueil : Liste des articles paginés
    public function index()
    {
        $posts = Post::with(['category', 'user'])->latest()->paginate(6);
        $categories = Category::withCount('posts')->orderBy('name')->get();
        $featuredPost = Post::orderByDesc('views')->first();
        
        $popularPosts = Post::orderByDesc('views')->take(5)->get();

        return view('posts.index', compact('posts', 'categories', 'featuredPost', 'popularPosts'));
    }

    // Filtrer par catégorie
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = Post::where('category_id', $category->id)->latest()->paginate(6);
        $categories = Category::withCount('posts')->orderBy('name')->get();
        $popularPosts = Post::orderByDesc('views')->take(5)->get();

        return view('posts.index', compact('posts', 'category', 'categories', 'popularPosts'));
    }

    // Page de lecture d'un article
    public function show($slug)
    {
        // 1. Récupérer l'article avec ses relations de base
        $post = Post::where('slug', $slug)->with(['category', 'user'])->firstOrFail();
        
        // 2. Incrémenter les vues
        $post->increment('views');

        // 3. RÉCUPÉRATION DES COMMENTAIRES PAGINÉS (Correction de l'erreur)
        // On récupère les commentaires parents, approuvés, avec pagination de 6
        $comments = $post->comments()
                        ->whereNull('parent_id')
                        ->where('status', 'approved')
                        ->latest()
                        ->paginate(4);

        // 4. Articles similaires
        $relatedPosts = Post::where('category_id', $post->category_id)
                            ->where('id', '!=', $post->id)
                            ->latest()
                            ->take(3)
                            ->get();

        // 5. Données sidebar
        $categories = Category::withCount('posts')->orderBy('name')->get();
        $popularPosts = Post::orderByDesc('views')->take(5)->get();

        // On passe bien 'comments' au compact pour que la vue puisse l'utiliser
        return view('posts.show', compact('post', 'relatedPosts', 'categories', 'popularPosts', 'comments'));
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('likes');
        return response()->json(['success' => true, 'likes' => $post->likes]);
    }
}