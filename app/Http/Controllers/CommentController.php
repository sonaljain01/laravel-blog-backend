<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function display(int $id)
    {
        $comments = Comment::with(['user:id,name,created_at', 'blog:id,name,created_at'])->where('blog_id', $id)->get();
        if (count($comments) == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No comments found',
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $comments,
        ]);
    }

    public function store(CommentStoreRequest $request)
    {

        $comment = Comment::create([
            'blog_id' => $request->blog_id,
            'comment' => $request->comment,
            'user_id' => auth()->user()->id,

        ]);

        if (! $comment) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to create comment',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Comment created successfully',
        ]);
    }
}
