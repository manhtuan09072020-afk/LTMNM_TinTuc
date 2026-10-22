@extends('layouts.admin')

@section('title', $post->title)
@section('heading', 'Chi tiết bài viết')

@section('content')
<div class="row g-3">
    <div class="col-xl-8">
        <div class="admin-card p-4">
            <span class="cat-badge mb-2" style="background: {{ $post->category->color }}">{{ $post->category->name }}</span>
            <h2 class="fw-bold">{{ $post->title }}</h2>
            <p class="article-summary my-3">{{ $post->summary }}</p>
            @if ($post->thumbnail_url)
                <img src="{{ $post->thumbnail_url }}" class="img-fluid rounded-3 mb-3 w-100" alt="">
            @endif
            <div class="article-content">{!! $post->content !!}</div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="admin-card p-4 mb-3">
            <h3 class="h6 fw-bold mb-3">Thông tin</h3>
            <dl class="row small mb-0">
                <dt class="col-5">Trạng thái</dt><dd class="col-7">@include('admin.posts.status')</dd>
                <dt class="col-5">Tác giả</dt><dd class="col-7">{{ $post->author->name }}</dd>
                <dt class="col-5">Ngày đăng</dt><dd class="col-7">{{ $post->published_at?->format('H:i d/m/Y') ?? '—' }}</dd>
                <dt class="col-5">Cập nhật</dt><dd class="col-7">{{ $post->updated_at->format('H:i d/m/Y') }}</dd>
                <dt class="col-5">Lượt xem</dt><dd class="col-7">{{ number_format($post->views, 0, ',', '.') }}</dd>
                <dt class="col-5">Nổi bật</dt><dd class="col-7">{{ $post->is_featured ? 'Có' : 'Không' }}</dd>
                <dt class="col-5">Thẻ</dt>
                <dd class="col-7">
                    @forelse ($post->tags as $tag)
                        <span class="badge text-bg-light">#{{ $tag->name }}</span>
                    @empty — @endforelse
                </dd>
            </dl>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-brand btn-sm flex-fill"><i class="bi bi-pencil me-1"></i>Sửa</a>
                <a href="{{ route('posts.show', $post) }}" target="_blank" class="btn btn-light btn-sm flex-fill"><i class="bi bi-box-arrow-up-right me-1"></i>Xem trên web</a>
            </div>
        </div>

        <div class="admin-card p-4">
            <h3 class="h6 fw-bold mb-3">Bình luận ({{ $post->comments->count() }})</h3>
            @forelse ($post->comments as $comment)
                <div class="d-flex gap-2 py-2 border-bottom">
                    <img src="{{ $comment->user->avatar_url }}" class="avatar-sm" alt="">
                    <div class="flex-grow-1 small">
                        <strong>{{ $comment->user->name }}</strong>
                        @unless ($comment->is_approved)<span class="badge text-bg-warning">Đã ẩn</span>@endunless
                        <div class="text-muted">{{ $comment->content }}</div>
                    </div>
                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" data-confirm="Xóa bình luận này?">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            @empty
                <p class="small text-muted mb-0">Chưa có bình luận.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
