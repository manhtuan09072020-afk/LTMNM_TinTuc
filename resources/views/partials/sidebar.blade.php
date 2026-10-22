{{-- Cột phải dùng chung cho các trang người dùng --}}
<aside class="d-flex flex-column gap-4">
    <div class="side-box">
        <h5 class="section-title fs-6 mb-2">Xem nhiều nhất</h5>
        @foreach ($popularPosts as $i => $popular)
            <a href="{{ route('posts.show', $popular) }}" class="popular-item">
                <span class="rank">{{ $i + 1 }}</span>
                <span>
                    <span class="fw-semibold line-2 d-block">{{ $popular->title }}</span>
                    <span class="meta"><i class="bi bi-eye"></i>{{ number_format($popular->views, 0, ',', '.') }} lượt xem</span>
                </span>
            </a>
        @endforeach
    </div>

    <div class="side-box">
        <h5 class="section-title fs-6 mb-2">Chuyên mục</h5>
        <div class="cat-list">
            @foreach ($sidebarCategories as $sideCategory)
                <a href="{{ route('categories.show', $sideCategory) }}">
                    <span><span class="cat-dot" style="background: {{ $sideCategory->color }}"></span>{{ $sideCategory->name }}</span>
                    <span class="badge rounded-pill text-bg-light">{{ $sideCategory->posts_count }}</span>
                </a>
            @endforeach
        </div>
    </div>

    @guest
        <div class="side-box text-center" style="background: linear-gradient(135deg, #c8102e, #7f0a1d); color: #fff;">
            <i class="bi bi-chat-heart fs-1"></i>
            <h6 class="fw-bold mt-2">Tham gia thảo luận</h6>
            <p class="small mb-3 opacity-75">Đăng ký tài khoản miễn phí để bình luận các bài viết.</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-sm rounded-pill px-4 fw-semibold">Đăng ký ngay</a>
        </div>
    @endguest
</aside>
