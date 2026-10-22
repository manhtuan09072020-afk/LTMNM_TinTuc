@extends('layouts.app')

@section('title', 'Đăng nhập')
@section('main-class', 'auth-wrap py-5')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card auth-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-brand text-white mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-lock fs-3"></i>
                    </div>
                    <h1 class="h3 fw-bold">Đăng nhập</h1>
                    <p class="text-muted">Chào mừng bạn quay lại Tin Tức 24h</p>
                </div>

                <form action="{{ route('login') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="ban@example.com" autofocus>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <button class="btn btn-brand w-100 py-2 fw-semibold">Đăng nhập</button>
                </form>

                <p class="text-center mt-4 mb-0">Chưa có tài khoản? <a href="{{ route('register') }}" class="text-brand fw-semibold">Đăng ký ngay</a></p>

                <div class="alert alert-light border small mt-4 mb-0">
                    <strong>Tài khoản demo:</strong><br>
                    Admin: admin@tintuc.test / Admin@123<br>
                    User: user@tintuc.test / User@123
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
