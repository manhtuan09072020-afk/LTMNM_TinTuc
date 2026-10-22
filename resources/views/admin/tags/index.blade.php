@extends('layouts.admin')

@section('title', 'Thẻ')
@section('heading', 'Quản lý thẻ')

@section('content')
<div class="admin-card p-4" style="max-width: 900px;">
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <form class="d-flex gap-2" method="GET">
            <input type="search" name="q" value="{{ $keyword }}" class="form-control" placeholder="Tìm thẻ...">
            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg me-1"></i>Thêm thẻ</a>
    </div>

    <table class="table table-hover align-middle">
        <thead class="table-light"><tr><th>#</th><th>Tên thẻ</th><th>Slug</th><th class="text-center">Số bài</th><th class="text-end">Thao tác</th></tr></thead>
        <tbody>
            @forelse ($tags as $tag)
                <tr>
                    <td>{{ $tags->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">#{{ $tag->name }}</td>
                    <td class="small text-muted">{{ $tag->slug }}</td>
                    <td class="text-center"><span class="badge text-bg-light">{{ $tag->posts_count }}</span></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline" data-confirm="Xóa thẻ '{{ $tag->name }}'?">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Không có thẻ nào.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $tags->links() }}
</div>
@endsection
