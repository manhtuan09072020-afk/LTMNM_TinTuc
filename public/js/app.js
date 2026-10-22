// ============ Tin Tức 24h — JavaScript dùng chung ============
document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    // 1. Tự ẩn thông báo flash sau 4 giây
    document.querySelectorAll('.toast-stack .alert').forEach((el) => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(el).close(), 4000);
    });

    // 2. Hộp xác nhận trước khi xóa: <form data-confirm="...">
    document.addEventListener('submit', (e) => {
        const msg = e.target.dataset.confirm;
        if (msg && !confirm(msg)) e.preventDefault();
    });

    // 3. Gợi ý tìm kiếm trực tiếp (AJAX)
    document.querySelectorAll('[data-suggest]').forEach((input) => {
        const box = input.parentElement.querySelector('.search-suggest');
        let timer;
        input.addEventListener('input', () => {
            clearTimeout(timer);
            const q = input.value.trim();
            if (q.length < 2) { box.style.display = 'none'; return; }
            timer = setTimeout(async () => {
                const res = await fetch(`${input.dataset.suggest}?q=${encodeURIComponent(q)}`, { headers: { Accept: 'application/json' } });
                const items = await res.json();
                box.innerHTML = items.length
                    ? items.map((i) => `<a href="${i.url}"><i class="bi bi-file-earmark-text me-2 text-muted"></i>${escapeHtml(i.title)}</a>`).join('')
                    : '<div class="px-3 py-2 text-muted small">Không tìm thấy bài viết phù hợp</div>';
                box.style.display = 'block';
            }, 250);
        });
        document.addEventListener('click', (e) => { if (!input.parentElement.contains(e.target)) box.style.display = 'none'; });
    });

    // 4. Gửi bình luận bằng AJAX (không tải lại trang)
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = commentForm.querySelector('button[type="submit"]');
            const textarea = commentForm.querySelector('textarea');
            const error = commentForm.querySelector('.invalid-feedback');
            btn.disabled = true;
            textarea.classList.remove('is-invalid');

            try {
                const res = await fetch(commentForm.action, {
                    method: 'POST',
                    headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: new FormData(commentForm),
                });
                const data = await res.json();
                if (res.status === 422) {
                    textarea.classList.add('is-invalid');
                    error.textContent = data.errors.content[0];
                } else if (res.ok) {
                    document.getElementById('no-comment')?.remove();
                    document.getElementById('comment-list').insertAdjacentHTML('afterbegin', data.html);
                    const counter = document.getElementById('comment-count');
                    counter.textContent = parseInt(counter.textContent, 10) + 1;
                    textarea.value = '';
                    showToast(data.message);
                } else {
                    showToast('Có lỗi xảy ra, vui lòng thử lại.', 'danger');
                }
            } finally {
                btn.disabled = false;
            }
        });
    }

    // 5. Xóa bình luận của mình bằng AJAX
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-delete-comment]');
        if (!btn || !confirm('Bạn chắc chắn muốn xóa bình luận này?')) return;
        const res = await fetch(btn.dataset.deleteComment, {
            method: 'DELETE',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
        });
        if (res.ok) {
            btn.closest('.comment-item').remove();
            const counter = document.getElementById('comment-count');
            if (counter) counter.textContent = Math.max(0, parseInt(counter.textContent, 10) - 1);
            showToast((await res.json()).message);
        }
    });

    // 6. Xem trước ảnh trước khi upload: <input type="file" data-preview="#img">
    document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
        input.addEventListener('change', () => {
            const file = input.files[0];
            const img = document.querySelector(input.dataset.preview);
            if (file && img) { img.src = URL.createObjectURL(file); img.classList.remove('d-none'); }
        });
    });

    // 7. Mở/đóng sidebar admin trên điện thoại
    document.querySelector('[data-toggle-sidebar]')?.addEventListener('click', () => {
        document.querySelector('.admin-sidebar').classList.toggle('show');
    });
});

function showToast(message, type = 'success') {
    let stack = document.querySelector('.toast-stack');
    if (!stack) {
        stack = document.createElement('div');
        stack.className = 'toast-stack';
        document.body.appendChild(stack);
    }
    const el = document.createElement('div');
    el.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
    el.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${escapeHtml(message)}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    stack.appendChild(el);
    setTimeout(() => bootstrap.Alert.getOrCreateInstance(el).close(), 4000);
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
