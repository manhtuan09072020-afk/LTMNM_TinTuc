<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q'));
        $state = $request->query('state');

        $comments = Comment::with(['user', 'post'])
            ->when($keyword, fn ($q) => $q->where('content', 'like', "%{$keyword}%"))
            ->when($state === 'approved', fn ($q) => $q->where('is_approved', true))
            ->when($state === 'hidden', fn ($q) => $q->where('is_approved', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.comments.index', compact('comments', 'keyword', 'state'));
    }

    // Ẩn / hiện bình luận
    public function toggle(Comment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => ! $comment->is_approved]);

        return back()->with('success', $comment->is_approved ? 'Đã hiển thị bình luận.' : 'Đã ẩn bình luận.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận.');
    }
}
