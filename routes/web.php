<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Khu vực công khai (ai cũng xem được)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tim-kiem', [PostController::class, 'search'])->name('search');
Route::get('/tim-kiem/goi-y', [PostController::class, 'suggest'])->name('search.suggest');
Route::get('/chuyen-muc/{category}', [PostController::class, 'category'])->name('categories.show');
Route::get('/the/{tag}', [PostController::class, 'tag'])->name('tags.show');
Route::get('/bai-viet/{post}', [PostController::class, 'show'])->name('posts.show');

/*
|--------------------------------------------------------------------------
| Đăng nhập / đăng ký (chỉ dành cho khách chưa đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Người dùng đã đăng nhập (role user hoặc admin)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');

    Route::post('/bai-viet/{post}/binh-luan', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/binh-luan/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/tai-khoan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/tai-khoan', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Khu vực quản trị /admin (chỉ role admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::resource('categories', Admin\CategoryController::class);
    Route::resource('tags', Admin\TagController::class)->except('show');

    Route::get('posts/trash', [Admin\PostController::class, 'trash'])->name('posts.trash');
    Route::patch('posts/{post}/restore', [Admin\PostController::class, 'restore'])->withTrashed()->name('posts.restore');
    Route::delete('posts/{post}/force', [Admin\PostController::class, 'forceDelete'])->withTrashed()->name('posts.force-delete');
    Route::resource('posts', Admin\PostController::class);

    Route::get('comments', [Admin\CommentController::class, 'index'])->name('comments.index');
    Route::patch('comments/{comment}/toggle', [Admin\CommentController::class, 'toggle'])->name('comments.toggle');
    Route::delete('comments/{comment}', [Admin\CommentController::class, 'destroy'])->name('comments.destroy');

    Route::resource('users', Admin\UserController::class)->except('show');
});
