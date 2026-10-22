{{-- Bài viết dạng danh sách ngang (dùng cho tìm kiếm, chuyên mục) --}}
<article class="post-card row g-3 align-items-center py-3 border-bottom">
    <div class="col-5 col-md-4">
        <a href="{{ route('posts.show', $post) }}" class="thumb d-block">
            @include('partials.thumb', ['post' => $post])
        </a>
    </div>
    <div class="col-7 col-md-8">
        <a href="{{ route('categories.show', $post->category) }}" class="cat-badge mb-2" style="background: {{ $post->category->color }}">{{ $post->category->name }}</a>
        <h3 class="title fs-5 mb-2 line-2"><a href="{{ route('posts.show', $post) }}">{!! $highlight ?? false ? preg_replace('/('.preg_quote(e($highlight), '/').')/iu', '<mark>$1</mark>', e($post->title)) : e($post->title) !!}</a></h3>
        <p class="summary line-2 mb-2 d-none d-md-block">{{ $post->summary }}</p>
        <div class="meta">
            <span class="me-3"><i class="bi bi-person"></i>{{ $post->author->name }}</span>
            <span class="me-3"><i class="bi bi-clock"></i>{{ $post->published_at?->format('d/m/Y H:i') }}</span>
            <span><i class="bi bi-eye"></i>{{ number_format($post->views, 0, ',', '.') }}</span>
        </div>
    </div>
</article>
