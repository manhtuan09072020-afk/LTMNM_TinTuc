# Tin Tức 24h — Website Tin tức bằng Laravel

Đồ án cuối môn **Lập trình mã nguồn mở** — Đề tài số 2: *Website Tin tức / Blog*.

Trang tin tức có hai khu vực:

- **Khu vực người dùng**: xem tin, lọc theo chuyên mục/thẻ, tìm kiếm, đăng ký/đăng nhập, bình luận.
- **Khu vực quản trị `/admin`**: quản lý bài viết, chuyên mục, thẻ, bình luận và người dùng.

## Công nghệ

| Thành phần | Công nghệ |
|---|---|
| Backend | Laravel 13 (PHP ≥ 8.3) |
| Cơ sở dữ liệu | MySQL 8 — Eloquent ORM, Migration, Seeder, Factory |
| Giao diện | Blade Template + Bootstrap 5.3 + Bootstrap Icons |
| Thư viện JS (CDN) | Quill 2 (trình soạn thảo bài viết), Chart.js 4 (biểu đồ trên dashboard) |
| Xác thực | Laravel Auth (`Auth::attempt`) + Middleware `auth` và `admin` tự viết |

## Cài đặt và chạy

Cần có: PHP ≥ 8.3 (bật `pdo_mysql`, `fileinfo`, `gd`), Composer, MySQL. Có thể dùng Laragon hoặc XAMPP.

```bash
# 1. Cài thư viện
composer install

# 2. Tạo file cấu hình
cp .env.example .env        # Windows: copy .env.example .env
php artisan key:generate

# 3. Tạo database trống tên tin_tuc_laravel (utf8mb4_unicode_ci) trong MySQL,
#    rồi sửa DB_USERNAME / DB_PASSWORD trong .env nếu cần

# 4. Tạo bảng và dữ liệu mẫu
php artisan migrate --seed

# 5. Tạo liên kết thư mục upload ảnh
php artisan storage:link

# 6. Chạy
php artisan serve
```

Mở http://localhost:8000

> **Cách khác cho bước 3–4:** import file [`database/tin_tuc_laravel.sql`](database/tin_tuc_laravel.sql) bằng phpMyAdmin/HeidiSQL. File này tự tạo database `tin_tuc_laravel` kèm dữ liệu mẫu.

> Ảnh minh họa của bài viết mẫu lấy từ picsum.photos nên cần có Internet. Ảnh upload từ trang quản trị được lưu trong `storage/app/public`.

### Tài khoản demo

| Vai trò | Email | Mật khẩu |
|---|---|---|
| Quản trị viên | admin@tintuc.test | Admin@123 |
| Biên tập viên (admin) | editor@tintuc.test | Admin@123 |
| Người dùng | user@tintuc.test | User@123 |

### Chạy kiểm thử tự động

```bash
php artisan test
```

23 test (dùng SQLite trong bộ nhớ, không ảnh hưởng dữ liệu MySQL) kiểm tra: phân quyền, đăng ký/đăng nhập, CRUD, upload ảnh, xóa mềm, tìm kiếm + phân trang, validation, bình luận AJAX.

## Chức năng

### Khách và người dùng

- Trang chủ: tin nổi bật, tin mới nhất (có phân trang), khối tin theo chuyên mục, bài xem nhiều.
- Xem bài viết theo chuyên mục và theo thẻ; trang chi tiết có đếm lượt xem, thời gian đọc ước tính và bài liên quan.
- **Tìm kiếm** theo từ khóa + chuyên mục + sắp xếp, có **phân trang và giữ từ khóa khi chuyển trang** (`withQueryString()`). Từ khóa được tô sáng trong kết quả.
- **Gợi ý tìm kiếm trực tiếp (AJAX)** khi gõ vào ô tìm kiếm trên header.
- Đăng ký, đăng nhập (có “ghi nhớ”, giới hạn 10 lần thử/phút), đăng xuất.
- **Bình luận bằng AJAX** (không tải lại trang); xóa bình luận của chính mình.
- Trang tài khoản: sửa thông tin, **upload ảnh đại diện**, đổi mật khẩu, xem lại bình luận của mình.

### Quản trị viên (`/admin`)

- Dashboard: thống kê tổng quan, top bài xem nhiều, biểu đồ số bài theo chuyên mục, bình luận mới.
- **Bài viết**: CRUD đầy đủ, trình soạn thảo, upload ảnh, gắn thẻ, nháp/xuất bản/hẹn giờ, tin nổi bật, lọc + tìm kiếm + phân trang, **thùng rác (Soft Delete)** với khôi phục và xóa vĩnh viễn.
- **Chuyên mục**: CRUD, màu hiển thị, bật/tắt; không cho xóa chuyên mục còn bài viết.
- **Thẻ**: CRUD (quan hệ nhiều–nhiều với bài viết).
- **Bình luận**: tìm kiếm, lọc, ẩn/hiện, xóa.
- **Người dùng**: CRUD, phân vai trò; không cho tự xóa hay tự hạ quyền của mình.

## Đối chiếu với yêu cầu đồ án

| Yêu cầu | Cách đáp ứng |
|---|---|
| Laravel, MySQL, Eloquent, Migration, Seeder/Factory | `database/migrations`, `database/seeders`, `database/factories`, `app/Models` |
| Blade + Bootstrap, tách khu vực người dùng / quản trị | `layouts/app.blade.php` và `layouts/admin.blade.php` |
| Tối thiểu 2 role, route được bảo vệ | `users.role` (admin/user); middleware `auth` + `admin` ([EnsureUserIsAdmin](app/Http/Middleware/EnsureUserIsAdmin.php)), xem [routes/web.php](routes/web.php) |
| CRUD trên ≥ 2 bảng có quan hệ | Chuyên mục 1–n Bài viết (`hasMany`/`belongsTo`), Bài viết n–n Thẻ (`belongsToMany`) |
| Tìm kiếm + phân trang, giữ từ khóa | `/tim-kiem` và mọi danh sách trong admin |
| Validation có thông báo lỗi rõ ràng | Form Request trong `app/Http/Requests`, thông báo tiếng Việt trong `lang/vi` |
| Flash message sau thêm/sửa/xóa | `partials/flash.blade.php` |
| Mở rộng (điểm cộng) | Upload ảnh qua Storage, AJAX (bình luận, gợi ý tìm kiếm), Soft Delete, Middleware phân quyền tự viết, responsive |

## Cấu trúc thư mục chính

```
app/Http/Controllers/        Controller khu vực người dùng
app/Http/Controllers/Admin/  Controller khu vực quản trị
app/Http/Controllers/Auth/   Đăng nhập, đăng ký, đăng xuất
app/Http/Middleware/         EnsureUserIsAdmin
app/Http/Requests/           Form Request (validation)
app/Models/                  User, Category, Post, Tag, Comment
database/                    Migration, Factory, Seeder, file SQL export
resources/views/             Giao diện Blade (layouts, partials, admin, auth, posts...)
public/css, public/js        CSS và JavaScript tự viết
tests/Feature/               Kiểm thử tự động
docs/                        Tài liệu thiết kế (ERD, Use Case) và ảnh chụp màn hình
```

Tài liệu phân tích – thiết kế: [docs/THIET_KE.md](docs/THIET_KE.md)
