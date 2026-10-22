<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q'));
        $categoryId = $request->integer('category') ?: null;
        $status = $request->query('status');

        $posts = Post::with(['category', 'author'])
            ->withCount('comments')
            ->search($keyword)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when(in_array($status, [Post::STATUS_DRAFT, Post::STATUS_PUBLISHED], true), fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $trashCount = Post::onlyTrashed()->count();

        return view('admin.posts.index', compact('posts', 'categories', 'keyword', 'categoryId', 'status', 'trashCount'));
    }

    public function create(): View
    {
        return view('admin.posts.form', [
            'post' => new Post(['status' => Post::STATUS_PUBLISHED]),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = Post::uniqueSlug($data['title']);

        $post = Post::create($data);
        $post->tags()->sync($request->validated('tags', []));

        return redirect()->route('admin.posts.index')->with('success', 'Đã thêm bài viết "'.Str::limit($post->title, 50).'".');
    }

    public function show(Post $post): View
    {
        $post->load(['category', 'author', 'tags', 'comments.user']);

        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', [
            'post' => $post->load('tags'),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $data = $this->prepareData($request, $post);

        if ($data['title'] !== $post->title) {
            $data['slug'] = Post::uniqueSlug($data['title'], $post->id);
        }

        $post->update($data);
        $post->tags()->sync($request->validated('tags', []));

        return redirect()->route('admin.posts.index')->with('success', 'Đã cập nhật bài viết.');
    }

    // Xóa mềm — chuyển vào thùng rác
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return back()->with('success', 'Đã chuyển bài viết vào thùng rác.');
    }

    public function trash(): View
    {
        $posts = Post::onlyTrashed()->with('category')->latest('deleted_at')->paginate(10);

        return view('admin.posts.trash', compact('posts'));
    }

    public function restore(Post $post): RedirectResponse
    {
        $post->restore();

        return back()->with('success', 'Đã khôi phục bài viết.');
    }

    // Xóa vĩnh viễn kèm ảnh đại diện đã upload
    public function forceDelete(Post $post): RedirectResponse
    {
        if ($post->thumbnail && ! Str::startsWith($post->thumbnail, 'http')) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->forceDelete();

        return back()->with('success', 'Đã xóa vĩnh viễn bài viết.');
    }

    private function prepareData(PostRequest $request, ?Post $post = null): array
    {
        $data = $request->safe()->except(['thumbnail', 'tags']);

        if ($request->hasFile('thumbnail')) {
            if ($post?->thumbnail && ! Str::startsWith($post->thumbnail, 'http')) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('posts', 'public');
        }

        // Xuất bản mà chưa chọn ngày thì lấy thời điểm hiện tại
        if ($data['status'] === Post::STATUS_PUBLISHED && empty($data['published_at'])) {
            $data['published_at'] = $post?->published_at ?? now();
        }

        return $data;
    }
}
