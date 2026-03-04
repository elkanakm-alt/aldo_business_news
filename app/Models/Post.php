<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'excerpt', 
        'content', 'image', 'status', 'views', 'likes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'views' => 'integer',
        'likes' => 'integer',
    ];

    /**
     * Boot : Automatisation du Slug et de l'Excerpt
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            // Création automatique d'un SLUG unique
            if (empty($post->slug)) {
                $slug = Str::slug($post->title);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                $post->slug = $slug;
            }

            // Création automatique de l'EXCERPT (Extrait)
            if (empty($post->excerpt)) {
                $post->excerpt = Str::limit(strip_tags($post->content), 160);
            }
        });
    }

    /* --- SCOPES (Pour filtrer facilement) --- */
    
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /* --- RELATIONS --- */

    public function category() 
    { 
        return $this->belongsTo(Category::class); 
    }

    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    public function comments() 
    { 
        return $this->hasMany(Comment::class); 
    }

    /* --- ACCESSORS (Logique de présentation) --- */

    /**
     * Gère l'URL de l'image (Local vs Externe/Cloudinary)
     * Appel : {{ $post->image_url }}
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->image) {
                return asset('images/default.jpg');
            }

            // Si c'est une URL complète (Cloudinary ou Web), on la renvoie telle quelle
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }

            // Sinon on pointe vers le stockage local de Laravel
            return asset('storage/' . $this->image);
        });
    }

    /**
     * Calcule le temps de lecture
     * Appel : {{ $post->reading_time }} min
     */
    protected function readingTime(): Attribute
    {
        return Attribute::get(function () {
            $wordCount = str_word_count(strip_tags($this->content));
            return ceil($wordCount / 200);
        });
    }
}