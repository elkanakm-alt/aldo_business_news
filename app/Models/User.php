<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * Champs assignables en masse
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'role',     // Pour Spatie
        'is_admin', // Pour droits admin classiques
    ];

    /**
     * Champs masqués pour le JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting automatique
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ACCESSOR : Génère l'URL de la photo automatiquement
     * Cette fonction permet d'appeler {{ Auth::user()->profile_photo }} dans tes vues
     */
    public function getProfilePhotoAttribute()
    {
        // 1. Si aucune photo n'est définie en BDD
        if (!$this->photo) {
            return asset('images/default-avatar.png'); 
        }

        // 2. Si la photo est stockée avec le préfixe "public/" (erreur courante)
        // on retire "public/" pour que asset('storage/...') fonctionne
        $path = str_replace('public/', '', $this->photo);

        // 3. On retourne l'URL vers le dossier storage
        return asset('storage/' . $path);
    }

    /**
     * Relation : un utilisateur peut avoir plusieurs posts
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}