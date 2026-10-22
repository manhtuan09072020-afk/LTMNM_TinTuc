@extends('layouts.admin')

@php $editing = $category->exists; @endphp
@section('title', $editing ? 'Sửa chuyên mục' : 'Thêm chuyên mục')
@section('heading', $editing ? 'Sửa chuyên mục' : 'Thêm chuyên mục')

@section('content')
<div class="admin-card p-4" style="max-width: 720px;">
    <form action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label" for="name">Tên chuyên mục <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="description">Mô tả</label>
            <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <label class="form-label" for="color">Màu hiển thị <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="color" class="form-control form-control-color" value="{{ old('color', $category->color) }}" oninput="document.getElementById('color').value = this.value">
                    <input type="text" id="color" name="color" value="{{ old('color', $category->color) }}" class="form-control @error('color') is-invalid @enderror">
                    @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-sm-6 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $category->is_active))>
                    <label class="form-check-label" for="is_active">Hiển thị trên trang web</label>
                </div>
            </div>
        </div>

        <button class="btn btn-brand px-4"><i class="bi bi-save me-1"></i>{{ $editing ? 'Cập nhật' : 'Thêm mới' }}</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Hủy</a>
    </form>
</div>
@endsection
