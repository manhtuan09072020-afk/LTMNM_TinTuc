@extends('layouts.admin')

@php
    $editing = $post->exists;
    $selectedTags = old('tags', $post->tags?->pluck('id')->all() ?? []);
@endphp
@section('title', $editing ? 'Sửa bài viết' : 'Viết bài mới')
@section('heading', $editing ? 'Sửa bài viết' : 'Viết bài mới')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<form action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" id="post-form" novalidate>
    @csrf
    @if ($editing) @method('PUT') @endif

    @if ($errors->any())
        <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>Vui lòng kiểm tra lại các trường được đánh dấu đỏ bên dưới.</div>
    @endif

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="admin-card p-4">
                <div class="mb-3">
                    <label class="form-label" for="title">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" class="form-control form-control-lg @error('title') is-invalid @enderror" placeholder="Nhập tiêu đề bài viết">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="summary">Tóm tắt <span class="text-danger">*</span></label>
                    <textarea id="summary" name="summary" rows="3" class="form-control @error('summary') is-invalid @enderror" placeholder="Đoạn mô tả ngắn hiển thị ở trang danh sách">{{ old('summary', $post->summary) }}</textarea>
                    @error('summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                    <div class="@error('content') is-invalid-editor @enderror">
                        <div id="editor">{!! old('content', $post->content) !!}</div>
                    </div>
                    <input type="hidden" name="content" id="content" value="{{ old('content', $post->content) }}">
                    @error('content')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="admin-card p-4 mb-3">
                <h2 class="h6 fw-bold mb-3">Xuất bản</h2>
                <div class="mb-3">
                    <label class="form-label" for="status">Trạng thái <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="published" @selected(old('status', $post->status) === 'published')>Xuất bản</option>
                        <option value="draft" @selected(old('status', $post->status) === 'draft')>Bản nháp</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="published_at">Ngày đăng</label>
                    <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="form-control @error('published_at') is-invalid @enderror">
                    @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Bỏ trống để đăng ngay. Chọn ngày tương lai để hẹn giờ.</div>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))>
                    <label class="form-check-label" for="is_featured">Đặt làm tin nổi bật</label>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-brand"><i class="bi bi-save me-1"></i>{{ $editing ? 'Cập nhật bài viết' : 'Lưu bài viết' }}</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-light">Hủy</a>
                </div>
            </div>

            <div class="admin-card p-4 mb-3">
                <label class="form-label" for="category_id">Chuyên mục <span class="text-danger">*</span></label>
                <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">-- Chọn chuyên mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id', $post->category_id) === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                <label class="form-label mt-3">Thẻ</label>
                <div class="d-flex flex-wrap gap-2" style="max-height: 160px; overflow-y: auto;">
                    @foreach ($tags as $tag)
                        <input type="checkbox" class="btn-check" name="tags[]" value="{{ $tag->id }}" id="tag-{{ $tag->id }}" @checked(in_array($tag->id, $selectedTags))>
                        <label class="btn btn-sm btn-outline-secondary rounded-pill" for="tag-{{ $tag->id }}">#{{ $tag->name }}</label>
                    @endforeach
                </div>
                @error('tags.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="admin-card p-4">
                <label class="form-label" for="thumbnail">Ảnh đại diện bài viết</label>
                <img id="thumb-preview" src="{{ $post->thumbnail_url }}" class="img-fluid rounded mb-2 {{ $post->thumbnail_url ? '' : 'd-none' }}" alt="">
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="form-control @error('thumbnail') is-invalid @enderror" data-preview="#thumb-preview">
                @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">JPG, PNG, WEBP — tối đa 2MB.</div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // Trình soạn thảo Quill: đồng bộ nội dung HTML vào input ẩn trước khi gửi form
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Soạn nội dung bài viết...',
        modules: {
            toolbar: [
                [{ header: [2, 3, false] }],
                ['bold', 'italic', 'underline', 'blockquote'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'image'],
                ['clean'],
            ],
        },
    });
    document.getElementById('post-form').addEventListener('submit', () => {
        document.getElementById('content').value = quill.getText().trim().length ? quill.root.innerHTML : '';
    });
</script>
@endpush
