<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'post_id'  => $postId,
            'user_id'  => Auth::id(),
            'content'  => $request->content,
            'name'     => Auth::user()->name,
            'approved' => false,    // En attente
            'status'   => 'pending' // En attente pour l'AdminHub
        ]);

        $admin = User::where('is_admin', true)->orWhere('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new NewCommentNotification($comment));
        }

        return back()->with('success', 'Votre commentaire est en attente de modération.');
    }
}