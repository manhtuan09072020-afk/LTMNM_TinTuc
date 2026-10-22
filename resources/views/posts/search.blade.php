@extends('layouts.app')

@section('title', $keyword ? 'Tìm kiếm: '.$keyword : 'Tìm kiếm')

@section('content')
<div class="container">
    <div class="row g-5">
        <div class="col-lg-8">
            <h1 class="section-title">Tìm kiếm bài viết</h1>

            {{-- Form tìm kiếm theo từ khóa + chuyên mục + sắp xếp --}}
            <form action="{{ route('search') }}" method="GET" class="row g-2 mb-4 p-3 rounded-3" style="background: var(--bg-soft);">
                <div class="col-md-6">
                    <input type="search" name="q" value="{{ $keyword }}" class="form-control" placeholder="Nhập từ khóa...">
                </div>
                <div class="col-6 col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Tất cả chuyên mục</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="sort" class="form-select">
                        <option value="newest" @selected($sort === 'newest')>Mới nhất</option>
                        <option value="popular" @selected($sort === 'popular')>Xem nhiều</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button class="btn btn-brand"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <p class="text-muted">
                @if ($keyword)
                    Tìm thấy <strong class="text-dark">{{ $posts->total() }}</strong> kết quả cho từ khóa <strong class="text-brand">"{{ $keyword }}"</strong>
                @else
                    Có <strong class="text-dark">{{ $posts->total() }}</strong> bài viết
                @endif
            </p>

            @forelse ($posts as $post)
                @include('partials.post-row', ['post' => $post, 'highlight' => $keyword])
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-search display-4 text-muted"></i>
                    <p class="mt-3 text-muted">Không tìm thấy bài viết nào phù hợp. Hãy thử từ khóa khác.</p>
                </div>
            @endforelse

            {{-- withQueryString() trong controller giúp giữ từ khóa khi chuyển trang --}}
            <div class="mt-4 d-flex justify-content-center">{{ $posts->links() }}</div>
        </div>

        <div class="col-lg-4">
            @include('partials.sidebar')
        </div>
    </div>
</div>
@endsection
