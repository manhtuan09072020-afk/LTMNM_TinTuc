<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trang chủ') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Tin Tức 24h — cập nhật tin tức nhanh, chính xác, đa chiều.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    {{-- Thanh trên cùng --}}
    <div class="topbar py-2 d-none d-md-block">
        <div class="container d-flex justify-content-between">
            <span><i class="bi bi-calendar3 me-1"></i>{{ ucfirst(now()->translatedFormat('l, d/m/Y')) }}</span>
            <span>
                <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
                <a href="#" class="me-3"><i class="bi bi-youtube"></i></a>
                <a href="#"><i class="bi bi-tiktok"></i></a>
            </span>
        </div>
    </div>

    {{-- Logo + tìm kiếm + tài khoản --}}
    <header class="site-header py-3">
        <div class="container d-flex align-items-center gap-3 flex-wrap">
            <a href="{{ route('home') }}" class="brand-logo me-auto">Tin Tức<span>24h</span></a>

            <form action="{{ route('search') }}" method="GET" class="search-box order-3 order-md-2 flex-grow-1 flex-md-grow-0" style="min-width: 280px;" autocomplete="off">
                <i class="bi bi-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Tìm kiếm tin tức..." value="{{ request('q') }}" data-suggest="{{ route('search.suggest') }}">
                <div class="search-suggest"></div>
            </form>

            <div class="order-2 order-md-3">
                @auth
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}" class="avatar-sm" alt="">
                            <span class="fw-semibold d-none d-sm-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            @if (auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Trang quản trị</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-2"></i>Tài khoản của tôi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-brand btn-sm rounded-pill px-3">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-brand btn-sm rounded-pill px-3 ms-1">Đăng ký</a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Menu chuyên mục --}}
    <nav class="main-nav sticky-top">
        <div class="container">
            <div class="nav-scroll">
                <ul class="nav flex-nowrap">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"><i class="bi bi-house-door-fill"></i></a>
                    </li>
                    @foreach ($navCategories as $navCategory)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->route('category')?->is($navCategory) ? 'active' : '' }}" href="{{ route('categories.show', $navCategory) }}">{{ $navCategory->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>

    @include('partials.flash')

    <main class="@yield('main-class', 'py-4')">
        @yield('content')
    </main>

    <footer class="site-footer pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <a href="{{ route('home') }}" class="brand-logo text-white">Tin Tức<span>24h</span></a>
                    <p class="mt-3 small">Trang tin tức tổng hợp — đồ án môn Lập trình mã nguồn mở, xây dựng bằng Laravel Framework.</p>
                </div>
                <div class="col-6 col-lg-4">
                    <h6 class="mb-3">Chuyên mục</h6>
                    <div class="row small">
                        @foreach ($navCategories as $navCategory)
                            <div class="col-6 mb-2"><a href="{{ route('categories.show', $navCategory) }}">{{ $navCategory->name }}</a></div>
                        @endforeach
                    </div>
                </div>
                <div class="col-6 col-lg-4">
                    <h6 class="mb-3">Liên hệ</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>140 Lê Trọng Tấn, Tân Phú, TP.HCM</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i>lienhe@tintuc.test</li>
                        <li><i class="bi bi-telephone me-2"></i>(028) 1234 5678</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="text-center small mb-0">&copy; {{ date('Y') }} Tin Tức 24h. Đồ án học phần Lập trình mã nguồn mở.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
