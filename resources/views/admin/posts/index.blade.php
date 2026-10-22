@extends('layouts.admin')

@section('title', 'Bài viết')
@section('heading', 'Quản lý bài viết')

@section('content')
<div class="admin-card p-4">
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <form class="d-flex flex-wrap gap-2" method="GET">
            <input type="search" name="q" value="{{ $keyword }}" class="form-control" style="width: 240px;" placeholder="Tìm theo tiêu đề, tóm tắt...">
            <select name="category" class="form-select" style="width: 180px;">
                <option value="">Tất cả chuyên mục</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select" style="width: 160px;">
                <option value="">Mọi trạng thái</option>
                <option value="published" @selected($status === 'published')>Đã xuất bản</option>
                <option value="draft" @selected($status === 'draft')>Bản nháp</option>
            </select>
            <button class="btn btn-outline-secondary"><i class="bi bi-funnel me-1"></i>Lọc</button>
            @if ($keyword || $categoryId || $status)
                <a href="{{ route('admin.posts.index') }}" class="btn btn-link text-muted">Xóa lọc</a>
            @endif
        </form>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.posts.trash') }}" class="btn btn-light"><i class="bi bi-trash3 me-1"></i>Thùng rác <span class="badge text-bg-secondary">{{ $trashCount }}</span></a>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg me-1"></i>Viết bài mới</a>
        </div>
    </div>

    <p class="small text-muted mb-2">Tổng cộng {{ $posts->total() }} bài viết</p>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 90px;">Ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Chuyên mục</th>
                    <th>Trạng thái</th>
                    <th class="text-center"><i class="bi bi-eye"></i></th>
                    <th class="text-center"><i class="bi bi-chat"></i></th>
                    <th>Ngày tạo</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            @if ($post->thumbnail_url)
                                <img src="{{ $post->thumbnail_url }}" class="table-thumb" alt="">
                            @else
                                <div class="table-thumb d-flex align-items-center justify-content-center text-white" style="background: {{ $post->category->color }}"><i class="bi bi-image"></i></div>
                            @endif
                        </td>
                        <td style="max-width: 360px;">
                            <a href="{{ route('admin.posts.show', $post) }}" class="fw-semibold line-2">{{ $post->title }}</a>
                            <div class="small text-muted">{{ $post->author->name }} @if ($post->is_featured)<span class="badge text-bg-warning ms-1"><i class="bi bi-star-fill"></i> Nổi bật</span>@endif</div>
                        </td>
                        <td><span class="cat-badge" style="background: {{ $post->category->color }}">{{ $post->category->name }}</span></td>
                        <td>@include('admin.posts.status')</td>
                        <td class="text-center small">{{ number_format($post->views, 0, ',', '.') }}</td>
                        <td class="text-center small">{{ $post->comments_count }}</td>
                        <td class="small text-nowrap">{{ $post->created_at->format('d/m/Y') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-sm btn-light" title="Xem"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-light" title="Sửa"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline" data-confirm="Chuyển bài viết này vào thùng rác?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Không tìm thấy bài viết nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $posts->links() }}
</div>
@endsection
