<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'name',
        'content',
        'approved', 
        'status' // Crucial pour l'AdminHub
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        // Récupère les réponses approuvées
        return $this->hasMany(Comment::class, 'parent_id')
            ->where(function($q) {
                $q->where('status', 'approved')->orWhere('approved', true);
            });
    }
}