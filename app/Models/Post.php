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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            // SLUG UNIQUE
            if (empty($post->slug)) {
                $slug = Str::slug($post->title);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                $post->slug = $slug;
            }

            // EXCERPT AUTO (Nettoyage des balises HTML pour un rendu propre)
            if (empty($post->excerpt)) {
                $post->excerpt = Str::limit(strip_tags($post->content), 160);
            }
        });
    }

    /* --- SCOPES --- */
    public function scopePublished($query)
    {
        // On s'adapte à ta logique : soit status='published', soit is_published=1
        return $query->where('status', 'published');
    }

    /* --- RELATIONS --- */
    public function category() { return $this->belongsTo(Category::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(Comment::class); }

    /* --- ACCESSORS (Syntaxe moderne) --- */
    
    // Pour l'image : {{ $post->image_url }}
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image 
            ? asset('storage/' . $this->image) 
            : asset('images/default.jpg'));
    }

    // Temps de lecture : {{ $post->reading_time }} min
    protected function readingTime(): Attribute
    {
        return Attribute::get(function () {
            $wordCount = str_word_count(strip_tags($this->content));
            return ceil($wordCount / 200);
        });
    }
}