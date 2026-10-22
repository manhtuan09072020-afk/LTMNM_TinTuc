@if ($post->isPublished())
    @if ($post->published_at?->isFuture())
        <span class="badge text-bg-info">Hẹn giờ {{ $post->published_at->format('d/m H:i') }}</span>
    @else
        <span class="badge text-bg-success">Đã xuất bản</span>
    @endif
@else
    <span class="badge text-bg-secondary">Bản nháp</span>
@endif
