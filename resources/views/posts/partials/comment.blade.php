<div class="comment-item {{ $comment->wasRecentlyCreated ? 'new' : '' }}">
    <img src="{{ $comment->user->avatar_url }}" alt="">
    <div class="bubble">
        <div class="d-flex align-items-center gap-2 mb-1">
            <strong>{{ $comment->user->name }}</strong>
            @if ($comment->user->isAdmin())
                <span class="badge bg-brand">Quản trị</span>
            @endif
            <span class="meta">· {{ $comment->created_at->diffForHumans() }}</span>
            @auth
                @if (auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                    <button type="button" class="btn btn-link btn-sm text-danger ms-auto p-0" data-delete-comment="{{ route('comments.destroy', $comment) }}" title="Xóa bình luận">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            @endauth
        </div>
        <p class="mb-0" style="white-space: pre-line;">{{ $comment->content }}</p>
    </div>
</div>
