<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Comment::with('user:id,name')->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $r)
    {
        $validated = $r->validate([
            'text' => 'required|string|max:500'
        ]);

        // Sanificazione minima anti-XSS (dimostrativa: valuta HTMLPurifier se ti serve HTML safe)
        $clean = strip_tags($validated['text']);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'text' => $clean
        ]);

        return response()->json($comment, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $comment->delete();
        return response()->noContent();
    }
}
