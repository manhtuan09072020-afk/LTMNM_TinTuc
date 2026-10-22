{{-- Ảnh bài viết; nếu không có ảnh thì hiển thị khối màu theo chuyên mục --}}
@if ($post->thumbnail_url)
    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" loading="lazy">
@else
    <div class="thumb-placeholder" style="background: {{ $post->category->color ?? '#6b7280' }}"><i class="bi bi-newspaper"></i></div>
@endif
