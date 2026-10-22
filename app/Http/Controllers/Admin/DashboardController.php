<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'posts' => Post::count(),
            'published' => Post::published()->count(),
            'categories' => Category::count(),
            'comments' => Comment::count(),
            'users' => User::count(),
            'views' => (int) Post::sum('views'),
        ];

        $topPosts = Post::with('category')->orderByDesc('views')->take(5)->get();
        $latestComments = Comment::with(['user', 'post'])->latest()->take(5)->get();

        // Số bài viết theo chuyên mục (dùng cho biểu đồ)
        $postsByCategory = Category::withCount('posts')->orderByDesc('posts_count')->get(['id', 'name', 'color']);

        return view('admin.dashboard', compact('stats', 'topPosts', 'latestComments', 'postsByCategory'));
    }
}
