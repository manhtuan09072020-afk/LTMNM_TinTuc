<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function show(Post $post): View
    {
        // Bài nháp chỉ admin được xem trước
        abort_unless($post->isPublished() || auth()->user()?->isAdmin(), 404);

        $post->increment('views');
        $post->load(['category', 'author', 'tags']);

        $comments = $post->comments()->approved()->with('user')->latest()->get();

        $related = Post::published()
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('posts.show', compact('post', 'comments', 'related'));
    }

    // Tìm kiếm theo từ khóa + chuyên mục, có phân trang và giữ từ khóa khi chuyển trang
    public function search(Request $request): View
    {
        $keyword = trim((string) $request->query('q'));
        $categoryId = $request->integer('category') ?: null;
        $sort = $request->query('sort') === 'popular' ? 'popular' : 'newest';

        $posts = Post::published()
            ->with(['category', 'author'])
            ->search($keyword)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($sort === 'popular', fn ($q) => $q->orderByDesc('views'), fn ($q) => $q->latest('published_at'))
            ->paginate(8)
            ->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('posts.search', compact('posts', 'keyword', 'categoryId', 'sort', 'categories'));
    }

    // Gợi ý tìm kiếm trực tiếp (AJAX) — trả về JSON
    public function suggest(Request $request)
    {
        $keyword = trim((string) $request->query('q'));

        if (mb_strlen($keyword) < 2) {
            return response()->json([]);
        }

        $posts = Post::published()->search($keyword)->latest('published_at')->take(6)->get(['title', 'slug']);

        return response()->json($posts->map(fn (Post $p) => [
            'title' => $p->title,
            'url' => route('posts.show', $p),
        ]));
    }

    public function category(Category $category): View
    {
        abort_unless($category->is_active, 404);

        $posts = $category->posts()->published()->with('author')->latest('published_at')->paginate(9);

        return view('posts.category', compact('category', 'posts'));
    }

    public function tag(Tag $tag): View
    {
        $posts = $tag->posts()->published()->with(['category', 'author'])->latest('published_at')->paginate(9);

        return view('posts.tag', compact('tag', 'posts'));
    }
}
