@extends('layouts.app')

@section('title', $post->title)
@section('description', $post->summary)

@section('content')
<div class="container">
    <div class="row g-5">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.show', $post->category) }}">{{ $post->category->name }}</a></li>
                    <li class="breadcrumb-item active text-truncate" style="max-width: 300px;">{{ $post->title }}</li>
                </ol>
            </nav>

            @unless ($post->isPublished())
                <div class="alert alert-warning"><i class="bi bi-eye-slash me-2"></i>Bài viết đang ở trạng thái <strong>nháp</strong> — chỉ quản trị viên xem được.</div>
            @endunless

            <article>
                <a href="{{ route('categories.show', $post->category) }}" class="cat-badge mb-3" style="background: {{ $post->category->color }}">{{ $post->category->name }}</a>
                <h1 class="article-title mb-3">{{ $post->title }}</h1>

                <div class="d-flex align-items-center gap-3 flex-wrap meta mb-4 pb-3 border-bottom">
                    <span class="d-flex align-items-center gap-2">
                        <img src="{{ $post->author->avatar_url }}" class="avatar-sm" alt="">
                        <strong class="text-dark">{{ $post->author->name }}</strong>
                    </span>
                    <span><i class="bi bi-calendar3"></i>{{ $post->published_at?->format('H:i, d/m/Y') ?? 'Chưa xuất bản' }}</span>
                    <span><i class="bi bi-eye"></i>{{ number_format($post->views, 0, ',', '.') }} lượt xem</span>
                    <span><i class="bi bi-hourglass-split"></i>{{ $post->reading_time }} phút đọc</span>
                    <span><i class="bi bi-chat"></i><span id="comment-count">{{ $comments->count() }}</span> bình luận</span>
                </div>

                <p class="article-summary mb-4">{{ $post->summary }}</p>

                @if ($post->thumbnail_url)
                    <figure class="mb-4">
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="img-fluid rounded-3 w-100">
                    </figure>
                @endif

                {{-- Nội dung HTML do quản trị viên soạn bằng trình soạn thảo --}}
                <div class="article-content">{!! $post->content !!}</div>

                @if ($post->tags->isNotEmpty())
                    <div class="mt-4 pt-3 border-top">
                        <i class="bi bi-tags me-2 text-muted"></i>
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('tags.show', $tag) }}" class="tag-pill">#{{ $tag->name }}</a>
                        @endforeach
                    </div>
                @endif
            </article>

            {{-- Bình luận --}}
            <section class="mt-5" id="binh-luan">
                <h2 class="section-title">Bình luận</h2>

                @auth
                    <form id="comment-form" action="{{ route('comments.store', $post) }}" method="POST" class="d-flex gap-3 mb-3">
                        @csrf
                        <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle" width="42" height="42" alt="">
                        <div class="flex-grow-1">
                            <textarea name="content" rows="3" class="form-control @error('content') is-invalid @enderror" placeholder="Chia sẻ ý kiến của bạn về bài viết...">{{ old('content') }}</textarea>
                            <div class="invalid-feedback">@error('content'){{ $message }}@enderror</div>
                            <div class="text-end mt-2">
                                <button type="submit" class="btn btn-brand rounded-pill px-4"><i class="bi bi-send me-1"></i>Gửi bình luận</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-light border d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span><i class="bi bi-info-circle me-2"></i>Vui lòng đăng nhập để tham gia bình luận.</span>
                        <a href="{{ route('login') }}" class="btn btn-brand btn-sm rounded-pill px-3">Đăng nhập</a>
                    </div>
                @endauth

                <div id="comment-list">
                    @forelse ($comments as $comment)
                        @include('posts.partials.comment')
                    @empty
                        <p class="text-muted" id="no-comment">Chưa có bình luận nào. Hãy là người đầu tiên!</p>
                    @endforelse
                </div>
            </section>

            {{-- Bài liên quan --}}
            @if ($related->isNotEmpty())
                <section class="mt-5">
                    <h2 class="section-title">Cùng chuyên mục</h2>
                    <div class="row g-4">
                        @foreach ($related as $item)
                            <div class="col-md-4">
                                @include('partials.post-card', ['post' => $item->setRelation('category', $post->category), 'showCategory' => false])
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <div class="col-lg-4">
            @include('partials.sidebar')
        </div>
    </div>
</div>
@endsection
