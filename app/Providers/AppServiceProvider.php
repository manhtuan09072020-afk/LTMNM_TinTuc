<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale('vi');

        // Menu chuyên mục dùng chung cho layout người dùng
        View::composer('layouts.app', function ($view) {
            $view->with('navCategories', Category::where('is_active', true)->orderBy('name')->get());
        });

        // Cột phải: bài xem nhiều + chuyên mục kèm số bài
        View::composer('partials.sidebar', function ($view) {
            $view->with('popularPosts', Post::published()->orderByDesc('views')->take(5)->get());
            $view->with('sidebarCategories', Category::where('is_active', true)
                ->withCount(['posts' => fn ($q) => $q->published()])
                ->orderBy('name')
                ->get());
        });
    }
}
