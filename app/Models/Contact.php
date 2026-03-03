<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts'; // Force la table plurielle

    protected $fillable = [
        'name', 
        'email', 
        'subject', 
        'message', 
        'reply', 
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}