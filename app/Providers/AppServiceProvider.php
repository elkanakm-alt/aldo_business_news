<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL; // <-- AJOUTÉ ICI
use App\Models\Contact;
use App\Models\Comment;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 0. Forcer le HTTPS en production (Correction Design)
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // 1. Pagination Tailwind
        Paginator::useTailwind();

        // 2. Dates en français
        Carbon::setLocale('fr');
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'fr');

        // 3. Partage des données pour la cloche (Contacts + Commentaires)
        View::composer('layouts.admin', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // Compte des messages de contact non lus
                $unreadContactsCount = Contact::where('is_read', false)->count();

                // Compte des commentaires en attente
                $unreadCommentsCount = Comment::where('status', 'pending')->count(); 

                // On additionne les deux pour la pastille rouge sur la cloche
                $totalNotifications = $unreadContactsCount + $unreadCommentsCount;

                // On garde tes notifications système classiques si tu les utilises
                $unreadSystemCount = $user->unreadNotifications()->count();
                $latestNotifs = $user->unreadNotifications()->limit(5)->get();
                
                $view->with([
                    'unreadContactsCount' => $unreadContactsCount,
                    'unreadCommentsCount' => $unreadCommentsCount,
                    'totalNotifications' => $totalNotifications,
                    'unreadNotificationsCount' => $unreadSystemCount,
                    'latestNotifications' => $latestNotifs
                ]);
            }
        });
    }
}