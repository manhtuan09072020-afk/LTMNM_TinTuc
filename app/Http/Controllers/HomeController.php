<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $base = Post::published()->with(['category', 'author']);

        $featured = (clone $base)->where('is_featured', true)->latest('published_at')->take(3)->get();

        $latest = (clone $base)
            ->whereNotIn('id', $featured->pluck('id'))
            ->latest('published_at')
            ->paginate(9);

        // Mỗi chuyên mục hiển thị 4 bài mới nhất
        $categoryBlocks = Category::where('is_active', true)
            ->withCount(['posts' => fn ($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->take(3)
            ->get()
            ->each(fn (Category $c) => $c->setRelation(
                'latestPosts',
                $c->posts()->published()->with('author')->latest('published_at')->take(4)->get()
            ));

        return view('home', compact('featured', 'latest', 'categoryBlocks'));
    }
}
