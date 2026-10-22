@extends('layouts.admin')

@section('title', 'Thùng rác')
@section('heading', 'Thùng rác bài viết')

@section('content')
<div class="admin-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i>Bài viết bị xóa mềm (Soft Delete) được lưu tại đây và có thể khôi phục.</p>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>Tiêu đề</th><th>Chuyên mục</th><th>Ngày xóa</th><th class="text-end">Thao tác</th></tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td class="fw-semibold">{{ $post->title }}</td>
                        <td><span class="cat-badge" style="background: {{ $post->category->color }}">{{ $post->category->name }}</span></td>
                        <td class="small">{{ $post->deleted_at->format('H:i d/m/Y') }}</td>
                        <td class="text-end text-nowrap">
                            <form action="{{ route('admin.posts.restore', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-arrow-counterclockwise me-1"></i>Khôi phục</button>
                            </form>
                            <form action="{{ route('admin.posts.force-delete', $post) }}" method="POST" class="d-inline" data-confirm="Xóa VĨNH VIỄN bài viết này? Thao tác không thể hoàn tác.">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-octagon me-1"></i>Xóa vĩnh viễn</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4"><i class="bi bi-trash3 fs-3 d-block mb-2"></i>Thùng rác trống.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $posts->links() }}
</div>
@endsection
