<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q'));

        $categories = Category::withCount('posts')
            ->when($keyword, fn ($q) => $q->where('name', 'like', "%{$keyword}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'keyword'));
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new Category(['color' => '#0d6efd', 'is_active' => true])]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create([...$request->validated(), 'slug' => Str::slug($request->validated('name'))]);

        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm chuyên mục mới.');
    }

    public function show(Category $category): View
    {
        $posts = $category->posts()->with('author')->latest()->paginate(10);

        return view('admin.categories.show', compact('category', 'posts'));
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update([...$request->validated(), 'slug' => Str::slug($request->validated('name'))]);

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật chuyên mục.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Không cho xóa chuyên mục còn bài viết (kể cả bài trong thùng rác) để tránh mất dữ liệu
        if ($category->posts()->withTrashed()->exists()) {
            return back()->with('error', 'Không thể xóa: chuyên mục "'.$category->name.'" vẫn còn bài viết.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa chuyên mục.');
    }
}
