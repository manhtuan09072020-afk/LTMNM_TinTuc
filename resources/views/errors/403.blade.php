@include('errors.page', ['code' => 403, 'icon' => 'shield-lock', 'title' => 'Không có quyền truy cập', 'text' => $exception->getMessage() ?: 'Bạn không có quyền truy cập trang này.'])
