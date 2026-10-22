{{-- Thẻ bài viết dạng lưới. Tham số: $post, $showCategory (mặc định true) --}}
<article class="post-card h-100">
    <a href="{{ route('posts.show', $post) }}" class="thumb d-block mb-3">
        @include('partials.thumb', ['post' => $post])
    </a>
    @if ($showCategory ?? true)
        <a href="{{ route('categories.show', $post->category) }}" class="cat-badge mb-2" style="background: {{ $post->category->color }}">{{ $post->category->name }}</a>
    @endif
    <h3 class="title fs-6 mb-2 line-2"><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
    <p class="summary line-2 mb-2">{{ $post->summary }}</p>
    <div class="meta">
        <span class="me-3"><i class="bi bi-clock"></i>{{ $post->published_at?->diffForHumans() }}</span>
        <span><i class="bi bi-eye"></i>{{ number_format($post->views, 0, ',', '.') }}</span>
    </div>
</article>
