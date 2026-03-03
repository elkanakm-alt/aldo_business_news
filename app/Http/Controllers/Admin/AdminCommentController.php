<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['post', 'user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->input('filter') === 'pending') {
            $query->where('status', 'pending')->orWhere('approved', false);
        }

        $comments = $query->latest()->paginate(10);
        $totalPending = Comment::where('status', 'pending')->orWhere('approved', false)->count();

        return view('admin.comments.index', compact('comments', 'totalPending'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['approved' => true, 'status' => 'approved']);
        return back()->with('success', 'Approuvé !');
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate(['reply_content' => 'required|string']);
        Comment::create([
            'post_id' => $comment->post_id,
            'user_id' => auth()->id(),
            'parent_id' => $comment->id,
            'name' => auth()->user()->name,
            'content' => $request->reply_content,
            'approved' => true,
            'status' => 'approved',
        ]);
        return back()->with('success', 'Réponse publiée.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Supprimé.');
    }
}