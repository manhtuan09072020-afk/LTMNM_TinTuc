@extends('layouts.app')

@section('title', $category->name)
@section('description', $category->description)

@section('content')
<div class="container">
    <div class="p-4 rounded-3 mb-4 text-white" style="background: linear-gradient(135deg, {{ $category->color }}, #1d2433);">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Trang chủ</a></li>
                <li class="breadcrumb-item active text-white">Chuyên mục</li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-1">{{ $category->name }}</h1>
        <p class="mb-0 opacity-75">{{ $category->description }} · {{ $posts->total() }} bài viết</p>
    </div>

    <div class="row g-5">
        <div class="col-lg-8">
            @forelse ($posts as $post)
                @include('partials.post-row', ['post' => $post->setRelation('category', $category)])
            @empty
                <p class="text-muted">Chuyên mục này chưa có bài viết.</p>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">{{ $posts->links() }}</div>
        </div>
        <div class="col-lg-4">
            @include('partials.sidebar')
        </div>
    </div>
</div>
@endsection
