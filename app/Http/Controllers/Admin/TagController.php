<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q'));

        $tags = Tag::withCount('posts')
            ->when($keyword, fn ($q) => $q->where('name', 'like', "%{$keyword}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.tags.index', compact('tags', 'keyword'));
    }

    public function create(): View
    {
        return view('admin.tags.form', ['tag' => new Tag()]);
    }

    public function store(TagRequest $request): RedirectResponse
    {
        Tag::create(['name' => $request->validated('name'), 'slug' => Str::slug($request->validated('name'))]);

        return redirect()->route('admin.tags.index')->with('success', 'Đã thêm thẻ mới.');
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tags.form', compact('tag'));
    }

    public function update(TagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update(['name' => $request->validated('name'), 'slug' => Str::slug($request->validated('name'))]);

        return redirect()->route('admin.tags.index')->with('success', 'Đã cập nhật thẻ.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete(); // bảng post_tag tự xóa theo (cascade)

        return redirect()->route('admin.tags.index')->with('success', 'Đã xóa thẻ.');
    }
}
