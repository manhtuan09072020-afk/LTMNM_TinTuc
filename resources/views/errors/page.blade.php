<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} — {{ $title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center text-center" style="min-height: 100vh; background: var(--bg-soft);">
    <div class="p-4">
        <i class="bi bi-{{ $icon }} text-brand" style="font-size: 4rem;"></i>
        <div class="display-1 fw-bold">{{ $code }}</div>
        <h1 class="h4 fw-bold">{{ $title }}</h1>
        <p class="text-muted">{{ $text }}</p>
        <a href="{{ url('/') }}" class="btn btn-brand rounded-pill px-4"><i class="bi bi-house me-1"></i>Về trang chủ</a>
    </div>
</body>
</html>
