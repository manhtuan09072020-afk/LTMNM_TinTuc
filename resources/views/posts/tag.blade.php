@extends('layouts.app')

@section('title', '#'.$tag->name)

@section('content')
<div class="container">
    <div class="row g-5">
        <div class="col-lg-8">
            <h1 class="section-title"><i class="bi bi-hash"></i>{{ $tag->name }} <span class="more">{{ $posts->total() }} bài viết</span></h1>

            @forelse ($posts as $post)
                @include('partials.post-row', ['post' => $post])
            @empty
                <p class="text-muted">Chưa có bài viết nào gắn thẻ này.</p>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">{{ $posts->links() }}</div>
        </div>
        <div class="col-lg-4">
            @include('partials.sidebar')
        </div>
    </div>
</div>
@endsection
