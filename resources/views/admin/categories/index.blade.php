@extends('layouts.admin')

@section('title', 'Chuyên mục')
@section('heading', 'Quản lý chuyên mục')

@section('content')
<div class="admin-card p-4">
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <form class="d-flex gap-2" method="GET">
            <input type="search" name="q" value="{{ $keyword }}" class="form-control" placeholder="Tìm theo tên chuyên mục...">
            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg me-1"></i>Thêm chuyên mục</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tên chuyên mục</th>
                    <th>Mô tả</th>
                    <th class="text-center">Số bài</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $categories->firstItem() + $loop->index }}</td>
                        <td><span class="cat-dot" style="background: {{ $category->color }}"></span><strong>{{ $category->name }}</strong><div class="small text-muted">/{{ $category->slug }}</div></td>
                        <td class="small text-muted" style="max-width: 320px;">{{ $category->description }}</td>
                        <td class="text-center"><span class="badge text-bg-light">{{ $category->posts_count }}</span></td>
                        <td class="text-center">
                            @if ($category->is_active)
                                <span class="badge text-bg-success">Hiển thị</span>
                            @else
                                <span class="badge text-bg-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-light" title="Xem"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-light" title="Sửa"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" data-confirm="Xóa chuyên mục '{{ $category->name }}'?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Không có chuyên mục nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $categories->links() }}
</div>
@endsection
