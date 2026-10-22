<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bảng điều khiển') | Quản trị {{ config('app.name') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="admin-body">
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand"><i class="bi bi-newspaper me-2"></i>Tin Tức<span>24h</span></a>

        <div class="menu-label">Tổng quan</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i>Bảng điều khiển</a>

        <div class="menu-label">Nội dung</div>
        <a href="{{ route('admin.posts.index') }}" class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-text"></i>Bài viết</a>
        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="bi bi-folder2-open"></i>Chuyên mục</a>
        <a href="{{ route('admin.tags.index') }}" class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}"><i class="bi bi-tags"></i>Thẻ</a>
        <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}"><i class="bi bi-chat-left-dots"></i>Bình luận</a>

        <div class="menu-label">Hệ thống</div>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-people"></i>Người dùng</a>
        <a href="{{ route('home') }}" class="nav-link" target="_blank"><i class="bi bi-box-arrow-up-right"></i>Xem trang web</a>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar px-3 px-md-4 py-2 d-flex align-items-center gap-3">
            <button class="btn btn-light d-lg-none" data-toggle-sidebar><i class="bi bi-list fs-5"></i></button>
            <h1 class="h5 fw-bold mb-0 me-auto">@yield('heading', 'Bảng điều khiển')</h1>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ auth()->user()->avatar_url }}" class="avatar-sm" alt="">
                    <span class="d-none d-sm-inline small fw-semibold">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-2"></i>Hồ sơ</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        @include('partials.flash')

        <main class="p-3 p-md-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
