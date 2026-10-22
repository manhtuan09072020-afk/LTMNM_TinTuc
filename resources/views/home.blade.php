@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<div class="container">
    {{-- Tin nổi bật --}}
    @if ($featured->isNotEmpty())
        <section class="row g-3 mb-5">
            @php $main = $featured->first(); @endphp
            <div class="col-lg-8">
                <a href="{{ route('posts.show', $main) }}" class="hero-main d-block">
                    @include('partials.thumb', ['post' => $main])
                    <div class="overlay">
                        <span class="cat-badge mb-2 align-self-start" style="background: {{ $main->category->color }}">{{ $main->category->name }}</span>
                        <h2 class="fs-2 mb-2">{{ $main->title }}</h2>
                        <p class="mb-2 opacity-75 d-none d-md-block line-2">{{ $main->summary }}</p>
                        <div class="small opacity-75"><i class="bi bi-person me-1"></i>{{ $main->author->name }} · <i class="bi bi-clock mx-1"></i>{{ $main->published_at->diffForHumans() }}</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 d-flex flex-column gap-3">
                @foreach ($featured->skip(1) as $side)
                    <a href="{{ route('posts.show', $side) }}" class="hero-side hero-main d-block flex-fill">
                        @include('partials.thumb', ['post' => $side])
                        <div class="overlay">
                            <span class="cat-badge mb-2 align-self-start" style="background: {{ $side->category->color }}">{{ $side->category->name }}</span>
                            <h3 class="mb-0 line-2">{{ $side->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <div class="row g-5">
        <div class="col-lg-8">
            {{-- Tin mới nhất (có phân trang) --}}
            <section class="mb-5" id="tin-moi">
                <h2 class="section-title">Tin mới nhất</h2>
                <div class="row g-4">
                    @forelse ($latest as $post)
                        <div class="col-sm-6 col-xl-4">
                            @include('partials.post-card', ['post' => $post])
                        </div>
                    @empty
                        <p class="text-muted">Chưa có bài viết nào.</p>
                    @endforelse
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $latest->fragment('tin-moi')->links() }}
                </div>
            </section>

            {{-- Khối tin theo chuyên mục --}}
            @foreach ($categoryBlocks as $block)
                @continue($block->latestPosts->isEmpty())
                <section class="mb-5">
                    <h2 class="section-title">
                        {{ $block->name }}
                        <a href="{{ route('categories.show', $block) }}" class="more">Xem tất cả <i class="bi bi-chevron-right"></i></a>
                    </h2>
                    <div class="row g-4">
                        @php $first = $block->latestPosts->first(); @endphp
                        <div class="col-md-6">
                            <a href="{{ route('posts.show', $first) }}" class="thumb d-block mb-3">@include('partials.thumb', ['post' => $first->setRelation('category', $block)])</a>
                            <h3 class="fs-5 fw-bold line-2"><a href="{{ route('posts.show', $first) }}">{{ $first->title }}</a></h3>
                            <p class="text-muted small line-3">{{ $first->summary }}</p>
                        </div>
                        <div class="col-md-6">
                            @foreach ($block->latestPosts->skip(1) as $item)
                                <a href="{{ route('posts.show', $item) }}" class="d-flex gap-3 mb-3 post-card">
                                    <span class="thumb flex-shrink-0" style="width: 120px;">@include('partials.thumb', ['post' => $item->setRelation('category', $block)])</span>
                                    <span>
                                        <span class="fw-semibold line-2">{{ $item->title }}</span>
                                        <span class="meta d-block mt-1"><i class="bi bi-clock"></i>{{ $item->published_at->diffForHumans() }}</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endforeach
        </div>

        <div class="col-lg-4">
            @include('partials.sidebar')
        </div>
    </div>
</div>
@endsection
