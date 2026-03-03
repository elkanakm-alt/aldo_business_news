<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create User
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create Categories
        $business = Category::create([
            'name' => 'Business',
            'slug' => 'business',
        ]);

        $tech = Category::create([
            'name' => 'Tech',
            'slug' => 'tech',
        ]);

        // Create Posts
        Post::create([
            'user_id' => $user->id,
            'category_id' => $business->id,
            'title' => 'Premier article ALDO',
            'slug' => 'premier-article-aldo',
            'excerpt' => 'Ceci est le résumé du premier article.',
            'content' => '<p>Contenu complet premium pour ALDO BUSINESS NEWS.</p>',
            'image' => null,
        ]);

        Post::create([
            'user_id' => $user->id,
            'category_id' => $tech->id,
            'title' => 'Deuxième article Tech',
            'slug' => 'deuxieme-article-tech',
            'excerpt' => 'Résumé article technologie.',
            'content' => '<p>Contenu complet Tech premium.</p>',
            'image' => null,
        ]);
    }
}
