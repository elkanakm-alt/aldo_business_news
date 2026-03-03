<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// --- CONTROLEURS PUBLICS ---
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController; 
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// --- CONTROLEURS ADMIN ---
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\ContactAdminController;

/*
|--------------------------------------------------------------------------
| ROUTE DE RÉPARATION ADMIN (Respecte ta structure is_admin)
|--------------------------------------------------------------------------
*/

Route::get('/force-admin', function () {
    // On récupère ton compte par l'email
    $user = User::where('email', 'admin@aldo.com')->first();

    if ($user) {
        // On force les droits admin sur ton compte existant
        $user->update(['is_admin' => true]);
        return "✅ Droits Admin activés pour admin@aldo.com ! <a href='/admin/dashboard'>Accéder au Panel Admin</a>";
    }

    // Si l'utilisateur n'existe pas encore, on le crée proprement
    User::create([
        'name' => 'Aldo Admin',
        'email' => 'admin@aldo.com',
        'password' => Hash::make('12345678'),
        'is_admin' => true,
        'email_verified_at' => now(),
    ]);

    return "🚀 Compte 'Aldo Admin' créé avec succès ! <a href='/login'>Connecte-toi ici</a>";
});

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/

Route::get('/', [PostController::class, 'index'])->name('home');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Articles & Catégories
Route::get('/article/{slug}', [PostController::class, 'show'])->name('post.show');
Route::get('/post/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/categorie/{slug}', [PostController::class, 'category'])->name('category.show');

// Système de Likes
Route::post('/post/{id}/like', [PostController::class, 'like'])->name('post.like');

// Pages Statiques
Route::view('/bible', 'pages.bible')->name('bible');
Route::view('/about', 'pages.about')->name('about');

// Contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| ROUTES MEMBRES (Dashboard Standard)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Rediriger automatiquement les admins vers le panel admin s'ils arrivent ici
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        
        $userName = auth()->user()->name;
        $commentsCount = \App\Models\Comment::where('name', $userName)->count();
        $recentComments = \App\Models\Comment::where('name', $userName)->with('post')->latest()->take(5)->get();
        return view('dashboard', compact('commentsCount', 'recentComments'));
    })->name('dashboard');

    Route::post('/post/{post}/comment', [CommentController::class, 'store'])->name('comments.store');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| ROUTES ADMINISTRATION (Le Panel Admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/contacts', [ContactAdminController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [ContactAdminController::class, 'show'])->name('contacts.show');
    Route::post('/contacts/{id}/reply', [ContactAdminController::class, 'reply'])->name('contacts.reply');
    Route::delete('/contacts/{id}', [ContactAdminController::class, 'destroy'])->name('contacts.destroy');

    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    Route::resource('posts', AdminPostController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('users', AdminUserController::class);
    
    Route::resource('comments', AdminCommentController::class)->only(['index', 'destroy']);
    Route::patch('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
    Route::post('comments/{comment}/reply', [AdminCommentController::class, 'reply'])->name('comments.reply');

    Route::get('/notifications/read', function () {
        auth()->user()->unreadNotifications->markAsRead(); 
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    })->name('notifications.read');
});

require __DIR__.'/auth.php';