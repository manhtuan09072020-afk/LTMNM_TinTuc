@extends('layouts.app')

@section('title', 'Đăng ký')
@section('main-class', 'auth-wrap py-5')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card auth-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-brand text-white mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-plus fs-3"></i>
                    </div>
                    <h1 class="h3 fw-bold">Tạo tài khoản</h1>
                    <p class="text-muted">Đăng ký để bình luận và thảo luận cùng cộng đồng</p>
                </div>

                <form action="{{ route('register') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">Họ tên</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nguyễn Văn A" autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="ban@example.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Tối thiểu 8 ký tự, gồm chữ và số">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">Nhập lại mật khẩu</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                    <button class="btn btn-brand w-100 py-2 fw-semibold">Đăng ký</button>
                </form>

                <p class="text-center mt-4 mb-0">Đã có tài khoản? <a href="{{ route('login') }}" class="text-brand fw-semibold">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
