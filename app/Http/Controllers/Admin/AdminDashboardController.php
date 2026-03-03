<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // --- STATISTIQUES GLOBALES ---
        $totalPosts = Post::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalViews = Post::sum('views') ?? 0;
        $totalLikes = Post::sum('likes') ?? 0;

        // --- DONNÉES GRAPHIQUE LIGNES (7 derniers jours) ---
        $labels = [];
        $dataViews = [];
        $dataLikes = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->translatedFormat('D j M'); // Format : Lun 24 Fév
            
            // On récupère les stats créées spécifiquement ce jour-là ou cumulées
            $dataViews[] = Post::whereDate('created_at', $date->toDateString())->sum('views');
            $dataLikes[] = Post::whereDate('created_at', $date->toDateString())->sum('likes');
        }

        // --- DONNÉES GRAPHIQUE CATÉGORIES (Cercle / Pie Chart) ---
        $categoriesData = Category::withCount('posts')->get();
        $catLabels = $categoriesData->pluck('name');
        $catCounts = $categoriesData->pluck('posts_count');

        // --- RÉCUPÉRATION DES LISTES ---
        // Derniers articles avec relations pour éviter les requêtes N+1
        $latestPosts = Post::with(['user', 'category'])->latest()->take(5)->get();
        
        // Derniers commentaires (pour la section modération rapide)
        $recentComments = Comment::with(['post', 'user'])->latest()->take(4)->get();

        // --- GESTION DES NOTIFICATIONS ---
        // On récupère les notifications non lues de l'utilisateur admin connecté
        $notifications = auth()->user() ? auth()->user()->unreadNotifications : collect();

        return view('admin.dashboard', compact(
            'totalPosts', 
            'totalUsers', 
            'totalCategories', 
            'totalViews', 
            'totalLikes',
            'latestPosts', 
            'notifications', 
            'recentComments',
            'labels', 
            'dataViews', 
            'dataLikes', 
            'catLabels', 
            'catCounts'
        ));
    }

    /**
     * Marquer toutes les notifications comme lues pour vider la cloche 🔔
     */
    public function markAsRead()
    {
        if (auth()->user()) {
            auth()->user()->unreadNotifications->markAsRead();
        }

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}