@extends('layouts.admin')

@php $editing = $user->exists; @endphp
@section('title', $editing ? 'Sửa tài khoản' : 'Thêm tài khoản')
@section('heading', $editing ? 'Sửa tài khoản' : 'Thêm tài khoản')

@section('content')
<div class="admin-card p-4" style="max-width: 720px;">
    <form action="{{ $editing ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="name">Họ tên <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="role">Vai trò <span class="text-danger">*</span></label>
                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" @disabled($user->is(auth()->user()))>
                    <option value="user" @selected(old('role', $user->role) === 'user')>Người dùng</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Quản trị viên</option>
                </select>
                @if ($user->is(auth()->user()))
                    <input type="hidden" name="role" value="admin">
                    <div class="form-text">Bạn không thể tự thay đổi vai trò của mình.</div>
                @endif
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <h6 class="fw-bold mt-4 mb-3">Mật khẩu @if ($editing)<span class="text-muted fw-normal small">(bỏ trống nếu không đổi)</span>@endif</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="password">Mật khẩu @unless ($editing)<span class="text-danger">*</span>@endunless</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="password_confirmation">Nhập lại mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-brand px-4"><i class="bi bi-save me-1"></i>{{ $editing ? 'Cập nhật' : 'Thêm mới' }}</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Hủy</a>
        </div>
    </form>
</div>
@endsection
