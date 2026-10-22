@extends('layouts.admin')

@section('title', $category->name)
@section('heading', 'Chi tiết chuyên mục')

@section('content')
<div class="admin-card p-4 mb-3">
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <div class="rounded-3" style="width: 56px; height: 56px; background: {{ $category->color }}"></div>
        <div class="me-auto">
            <h2 class="h4 fw-bold mb-1">{{ $category->name }}</h2>
            <div class="text-muted small">/{{ $category->slug }} · {{ $posts->total() }} bài viết · {{ $category->is_active ? 'Đang hiển thị' : 'Đang ẩn' }}</div>
            <p class="mb-0 mt-1">{{ $category->description }}</p>
        </div>
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil me-1"></i>Sửa</a>
        <a href="{{ route('categories.show', $category) }}" target="_blank" class="btn btn-light"><i class="bi bi-box-arrow-up-right me-1"></i>Xem trên web</a>
    </div>
</div>

<div class="admin-card p-4">
    <h3 class="h6 fw-bold mb-3">Bài viết trong chuyên mục</h3>
    <table class="table table-hover">
        <thead class="table-light"><tr><th>Tiêu đề</th><th>Tác giả</th><th>Trạng thái</th><th class="text-end">Lượt xem</th></tr></thead>
        <tbody>
            @forelse ($posts as $post)
                <tr>
                    <td><a href="{{ route('admin.posts.show', $post) }}" class="fw-semibold">{{ $post->title }}</a></td>
                    <td class="small">{{ $post->author->name }}</td>
                    <td>@include('admin.posts.status', ['post' => $post])</td>
                    <td class="text-end">{{ number_format($post->views, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Chưa có bài viết.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $posts->links() }}
</div>
@endsection
