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
| OUTILS DE MAINTENANCE (À supprimer après usage)
|--------------------------------------------------------------------------
*/

// Configuration finale : Images + Cache
Route::get('/final-setup', function () {
    Artisan::call('storage:link'); // Crée le lien pour afficher les images
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    return "✅ Images liées et cache nettoyé ! <a href='/'>Retour à l'accueil</a>";
});

// Forcer la création de l'admin
Route::get('/force-admin', function () {
    $user = User::updateOrCreate(
        ['email' => 'admin@aldo.com'],
        [
            'name' => 'Aldo Admin',
            'password' => Hash::make('12345678'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]
    );
    return "🚀 Compte Admin opérationnel ! <a href='/login'>Se connecter</a>";
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
| ROUTES MEMBRES (Connectés)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        // Redirection automatique si admin
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        
        $userName = auth()->user()->name;
        $commentsCount = \App\Models\Comment::where('name', $userName)->count();
        $recentComments = \App\Models\Comment::where('name', $userName)
                            ->with('post')->latest()->take(5)->get();
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
| ROUTES ADMINISTRATION
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des Contacts
    Route::controller(ContactAdminController::class)->group(function () {
        Route::get('/contacts', 'index')->name('contacts.index');
        Route::get('/contacts/{id}', 'show')->name('contacts.show');
        Route::post('/contacts/{id}/reply', 'reply')->name('contacts.reply');
        Route::delete('/contacts/{id}', 'destroy')->name('contacts.destroy');
    });

    // Profil Admin
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Ressources CRUD
    Route::resource('posts', AdminPostController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('users', AdminUserController::class);
    
    // Gestion des Commentaires
    Route::resource('comments', AdminCommentController::class)->only(['index', 'destroy']);
    Route::patch('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
    Route::post('comments/{comment}/reply', [AdminCommentController::class, 'reply'])->name('comments.reply');

    // Notifications
    Route::get('/notifications/read', function () {
        auth()->user()->unreadNotifications->markAsRead(); 
        return back()->with('success', 'Notifications lues.');
    })->name('notifications.read');
});

require __DIR__.'/auth.php';