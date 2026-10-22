@extends('layouts.app')

@section('title', 'Tài khoản của tôi')

@section('content')
<div class="container">
    <h1 class="section-title">Tài khoản của tôi</h1>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <img id="avatar-preview" src="{{ $user->avatar_url }}" class="rounded-circle border" width="90" height="90" style="object-fit: cover;" alt="">
                            <div class="flex-grow-1">
                                <label class="form-label" for="avatar">Ảnh đại diện</label>
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="form-control @error('avatar') is-invalid @enderror" data-preview="#avatar-preview">
                                @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">JPG, PNG, WEBP — tối đa 2MB.</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="name">Họ tên</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <h6 class="fw-bold mt-4 mb-3"><i class="bi bi-shield-lock me-2"></i>Đổi mật khẩu <span class="text-muted fw-normal small">(bỏ trống nếu không đổi)</span></h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="current_password">Mật khẩu hiện tại</label>
                                <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="password">Mật khẩu mới</label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="password_confirmation">Nhập lại mật khẩu mới</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <button class="btn btn-brand mt-4 px-4"><i class="bi bi-save me-1"></i>Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-chat-left-text me-2"></i>Bình luận của tôi ({{ $comments->total() }})</h6>
                    @forelse ($comments as $comment)
                        <div class="border-bottom py-2">
                            <a href="{{ route('posts.show', $comment->post) }}#binh-luan" class="fw-semibold small line-2">{{ $comment->post->title }}</a>
                            <p class="mb-1 small text-muted">"{{ \Illuminate\Support\Str::limit($comment->content, 100) }}"</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="meta">{{ $comment->created_at->diffForHumans() }}
                                    @unless ($comment->is_approved) · <span class="text-warning">Đã bị ẩn</span> @endunless
                                </span>
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" data-confirm="Xóa bình luận này?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-link btn-sm text-danger p-0"><i class="bi bi-trash"></i> Xóa</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Bạn chưa có bình luận nào.</p>
                    @endforelse
                    <div class="mt-3">{{ $comments->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
