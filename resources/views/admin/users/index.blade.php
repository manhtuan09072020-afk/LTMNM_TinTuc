@extends('layouts.admin')

@section('title', 'Người dùng')
@section('heading', 'Quản lý người dùng')

@section('content')
<div class="admin-card p-4">
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <form class="d-flex flex-wrap gap-2" method="GET">
            <input type="search" name="q" value="{{ $keyword }}" class="form-control" style="width: 260px;" placeholder="Tìm theo tên hoặc email...">
            <select name="role" class="form-select" style="width: 160px;">
                <option value="">Mọi vai trò</option>
                <option value="admin" @selected($role === 'admin')>Quản trị viên</option>
                <option value="user" @selected($role === 'user')>Người dùng</option>
            </select>
            <button class="btn btn-outline-secondary"><i class="bi bi-funnel me-1"></i>Lọc</button>
        </form>
        <a href="{{ route('admin.users.create') }}" class="btn btn-brand"><i class="bi bi-person-plus me-1"></i>Thêm tài khoản</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>Tài khoản</th><th>Vai trò</th><th class="text-center">Bài viết</th><th class="text-center">Bình luận</th><th>Ngày tạo</th><th class="text-end">Thao tác</th></tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $user->avatar_url }}" class="avatar-sm" alt="">
                                <div>
                                    <div class="fw-semibold">{{ $user->name }} @if ($user->is(auth()->user()))<span class="badge text-bg-light">Bạn</span>@endif</div>
                                    <div class="small text-muted">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($user->isAdmin())
                                <span class="badge bg-brand">Quản trị viên</span>
                            @else
                                <span class="badge text-bg-secondary">Người dùng</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $user->posts_count }}</td>
                        <td class="text-center">{{ $user->comments_count }}</td>
                        <td class="small">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                            @unless ($user->is(auth()->user()))
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" data-confirm="Xóa tài khoản {{ $user->email }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Không tìm thấy tài khoản nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
@endsection
