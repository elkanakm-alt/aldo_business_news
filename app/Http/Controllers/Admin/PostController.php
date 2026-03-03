<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'user'])->latest()->paginate(6)->withQueryString();
        $categories = Category::withCount('posts')->orderBy('name')->get();
        $latestPosts = Post::latest()->take(5)->get();
        $featuredPost = Post::orderByDesc('views')->first();
        $popularPosts = Post::where('id', '!=', optional($featuredPost)->id)->orderByDesc('views')->take(4)->get();

        return view('posts.index', compact('posts', 'categories', 'latestPosts', 'popularPosts', 'featuredPost'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = Post::where('category_id', $category->id)->with(['category', 'user'])->latest()->paginate(6);
        $categories = Category::withCount('posts')->orderBy('name')->get();
        $latestPosts = Post::latest()->take(5)->get();
        $featuredPost = null;
        $popularPosts = Post::orderByDesc('views')->take(4)->get(); 

        return view('posts.index', compact('posts', 'categories', 'latestPosts', 'popularPosts', 'featuredPost', 'category'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with(['category', 'user', 'comments' => function($q) {
            $q->where('approved', true)->latest();
        }])->firstOrFail();

        $categories = Category::withCount('posts')->orderBy('name')->get();
        $popularPosts = Post::orderByDesc('views')->take(4)->get();
        $latestPosts = Post::latest()->take(5)->get();

        // Incrément des vues
        $post->increment('views');

        return view('posts.show', compact('post', 'categories', 'popularPosts', 'latestPosts'));
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('likes');
        
        return response()->json([
            'success' => true,
            'likes' => $post->likes
        ]);
    }
}