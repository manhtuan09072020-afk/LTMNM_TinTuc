@extends('layouts.admin')

@section('title', 'Bảng điều khiển')
@section('heading', 'Bảng điều khiển')

@section('content')
<div class="row g-3 mb-4">
    @php
        $cards = [
            ['Bài viết', $stats['posts'], 'file-earmark-text', 'linear-gradient(135deg,#c8102e,#e5484d)', $stats['published'].' đã xuất bản'],
            ['Chuyên mục', $stats['categories'], 'folder2-open', 'linear-gradient(135deg,#2563eb,#60a5fa)', 'Đang hoạt động'],
            ['Bình luận', $stats['comments'], 'chat-left-dots', 'linear-gradient(135deg,#059669,#34d399)', 'Từ độc giả'],
            ['Lượt xem', number_format($stats['views'], 0, ',', '.'), 'eye', 'linear-gradient(135deg,#7c3aed,#a78bfa)', $stats['users'].' tài khoản'],
        ];
    @endphp
    @foreach ($cards as [$label, $value, $icon, $bg, $sub])
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="background: {{ $bg }}">
                <div class="small opacity-75">{{ $label }}</div>
                <div class="value">{{ $value }}</div>
                <div class="small opacity-75">{{ $sub }}</div>
                <i class="bi bi-{{ $icon }}"></i>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-xl-7">
        <div class="admin-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 fw-bold mb-0">Bài viết xem nhiều nhất</h2>
                <a href="{{ route('admin.posts.index') }}" class="small text-brand">Tất cả bài viết</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <tbody>
                        @foreach ($topPosts as $post)
                            <tr>
                                <td style="width: 80px;">
                                    @if ($post->thumbnail_url)<img src="{{ $post->thumbnail_url }}" class="table-thumb" alt="">@endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.posts.show', $post) }}" class="fw-semibold line-2">{{ $post->title }}</a>
                                    <span class="cat-badge mt-1" style="background: {{ $post->category->color }}">{{ $post->category->name }}</span>
                                </td>
                                <td class="text-end text-nowrap"><i class="bi bi-eye text-muted me-1"></i>{{ number_format($post->views, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="admin-card p-4 h-100">
            <h2 class="h6 fw-bold mb-3">Số bài viết theo chuyên mục</h2>
            <canvas id="categoryChart" height="260"></canvas>
        </div>
    </div>
    <div class="col-12">
        <div class="admin-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 fw-bold mb-0">Bình luận mới nhất</h2>
                <a href="{{ route('admin.comments.index') }}" class="small text-brand">Quản lý bình luận</a>
            </div>
            @foreach ($latestComments as $comment)
                <div class="d-flex gap-3 py-2 border-bottom">
                    <img src="{{ $comment->user->avatar_url }}" class="avatar-sm" alt="">
                    <div class="flex-grow-1">
                        <strong class="small">{{ $comment->user->name }}</strong>
                        <span class="meta">trong</span>
                        <a href="{{ route('posts.show', $comment->post) }}" class="small fw-semibold" target="_blank">{{ \Illuminate\Support\Str::limit($comment->post->title, 60) }}</a>
                        <div class="small text-muted">{{ $comment->content }}</div>
                    </div>
                    <span class="meta text-nowrap">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: @json($postsByCategory->pluck('name')),
            datasets: [{
                data: @json($postsByCategory->pluck('posts_count')),
                backgroundColor: @json($postsByCategory->pluck('color')),
                borderWidth: 2,
            }],
        },
        options: { plugins: { legend: { position: 'right' } }, cutout: '60%' },
    });
</script>
@endpush
