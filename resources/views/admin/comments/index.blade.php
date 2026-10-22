@extends('layouts.admin')

@section('title', 'Bình luận')
@section('heading', 'Quản lý bình luận')

@section('content')
<div class="admin-card p-4">
    <form class="d-flex flex-wrap gap-2 mb-3" method="GET">
        <input type="search" name="q" value="{{ $keyword }}" class="form-control" style="width: 260px;" placeholder="Tìm trong nội dung bình luận...">
        <select name="state" class="form-select" style="width: 170px;">
            <option value="">Tất cả</option>
            <option value="approved" @selected($state === 'approved')>Đang hiển thị</option>
            <option value="hidden" @selected($state === 'hidden')>Đã ẩn</option>
        </select>
        <button class="btn btn-outline-secondary"><i class="bi bi-funnel me-1"></i>Lọc</button>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>Người bình luận</th><th>Nội dung</th><th>Bài viết</th><th>Thời gian</th><th>Trạng thái</th><th class="text-end">Thao tác</th></tr>
            </thead>
            <tbody>
                @forelse ($comments as $comment)
                    <tr>
                        <td class="text-nowrap">
                            <img src="{{ $comment->user->avatar_url }}" class="avatar-sm me-2" alt="">
                            <span class="small fw-semibold">{{ $comment->user->name }}</span>
                        </td>
                        <td class="small" style="max-width: 320px;">{{ $comment->content }}</td>
                        <td class="small" style="max-width: 240px;"><a href="{{ route('posts.show', $comment->post) }}" target="_blank" class="line-2">{{ $comment->post->title }}</a></td>
                        <td class="small text-nowrap">{{ $comment->created_at->format('H:i d/m/Y') }}</td>
                        <td>
                            @if ($comment->is_approved)
                                <span class="badge text-bg-success">Hiển thị</span>
                            @else
                                <span class="badge text-bg-warning">Đã ẩn</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <form action="{{ route('admin.comments.toggle', $comment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-light" title="{{ $comment->is_approved ? 'Ẩn' : 'Hiện' }}"><i class="bi bi-{{ $comment->is_approved ? 'eye-slash' : 'eye' }}"></i></button>
                            </form>
                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline" data-confirm="Xóa bình luận này?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Không có bình luận nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $comments->links() }}
</div>
@endsection
