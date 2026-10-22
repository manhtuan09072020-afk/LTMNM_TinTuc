<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'category_id', 'user_id', 'title', 'slug', 'summary', 'content',
        'thumbnail', 'status', 'is_featured', 'views', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    // Bài viết thuộc về một chuyên mục
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Bài viết thuộc về một tác giả
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // Chỉ lấy bài đã xuất bản và đến ngày đăng
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    // Tìm theo từ khóa trong tiêu đề / tóm tắt
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (! filled($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('summary', 'like', "%{$keyword}%");
        });
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->thumbnail) {
                return null;
            }

            return Str::startsWith($this->thumbnail, ['http://', 'https://'])
                ? $this->thumbnail
                : Storage::url($this->thumbnail);
        });
    }

    // Thời gian đọc ước tính (200 từ/phút)
    protected function readingTime(): Attribute
    {
        return Attribute::get(fn () => max(1, (int) ceil(str_word_count(strip_tags($this->content)) / 200)));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Tạo slug không trùng từ tiêu đề
    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'bai-viet';
        $slug = $base;
        $i = 2;

        while (static::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
