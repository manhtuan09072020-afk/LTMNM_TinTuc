@extends('layouts.admin')

@php $editing = $tag->exists; @endphp
@section('title', $editing ? 'Sửa thẻ' : 'Thêm thẻ')
@section('heading', $editing ? 'Sửa thẻ' : 'Thêm thẻ')

@section('content')
<div class="admin-card p-4" style="max-width: 560px;">
    <form action="{{ $editing ? route('admin.tags.update', $tag) : route('admin.tags.store') }}" method="POST" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="mb-4">
            <label class="form-label" for="name">Tên thẻ <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $tag->name) }}" class="form-control @error('name') is-invalid @enderror" autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button class="btn btn-brand px-4"><i class="bi bi-save me-1"></i>{{ $editing ? 'Cập nhật' : 'Thêm mới' }}</button>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-light">Hủy</a>
    </form>
</div>
@endsection
