<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Importation nécessaire pour le slug

class PostController extends Controller
{
    // Affiche la liste des articles
    public function index()
    {
        $posts = Post::with(['category', 'user'])->latest()->paginate(6);
        $categories = Category::withCount('posts')->orderBy('name')->get();
        $featuredPost = Post::orderByDesc('views')->first();
        $popularPosts = Post::orderByDesc('views')->take(5)->get();

        return view('posts.index', compact('posts', 'categories', 'featuredPost', 'popularPosts'));
    }

    // Affiche les articles d'une catégorie spécifique
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = Post::where('category_id', $category->id)->latest()->paginate(6);
        $categories = Category::withCount('posts')->orderBy('name')->get();
        $popularPosts = Post::orderByDesc('views')->take(5)->get();

        return view('posts.index', compact('posts', 'category', 'categories', 'popularPosts'));
    }

    // Affiche un article seul
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with(['category', 'user'])->firstOrFail();
        $post->increment('views');

        $commentsList = $post->comments()
                        ->whereNull('parent_id')
                        ->where('status', 'approved')
                        ->latest()
                        ->paginate(4, ['*'], 'comments_page');

        $relatedPosts = Post::where('category_id', $post->category_id)
                            ->where('id', '!=', $post->id)
                            ->latest()
                            ->take(3)
                            ->get();

        $categories = Category::withCount('posts')->orderBy('name')->get();
        $popularPosts = Post::orderByDesc('views')->take(5)->get();

        return view('posts.show', compact('post', 'relatedPosts', 'categories', 'popularPosts', 'commentsList'));
    }

    // Affiche le formulaire de création (souvent accessible via /posts/create)
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    // Enregistre l'article dans la base de données
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Préparation des données
        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($request->title) . '-' . time();

        // 3. Gestion de l'image locale
        if ($request->hasFile('image')) {
            // Sauvegarde dans storage/app/public/posts
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }

        // 4. Création
        Post::create($data);

        return redirect()->route('posts.index')->with('success', 'Article publié !');
    }

    // Système de Like en AJAX
    public function like($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('likes');
        return response()->json(['success' => true, 'likes' => $post->likes]);
    }
}