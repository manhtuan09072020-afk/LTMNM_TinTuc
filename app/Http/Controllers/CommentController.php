<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Thêm bình luận — hỗ trợ cả gửi form thường và AJAX
    public function store(CommentRequest $request, Post $post)
    {
        abort_unless($post->isPublished(), 404);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->validated('content'),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Đã gửi bình luận.',
                'html' => view('posts.partials.comment', ['comment' => $comment->load('user')])->render(),
            ], 201);
        }

        return back()->with('success', 'Đã gửi bình luận.');
    }

    // Người dùng xóa bình luận của chính mình (admin xóa được mọi bình luận)
    public function destroy(Request $request, Comment $comment)
    {
        abort_unless($request->user()->id === $comment->user_id || $request->user()->isAdmin(), 403);

        $comment->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Đã xóa bình luận.']);
        }

        return back()->with('success', 'Đã xóa bình luận.');
    }
}
