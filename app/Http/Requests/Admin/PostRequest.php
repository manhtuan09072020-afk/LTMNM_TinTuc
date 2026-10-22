<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_featured' => $this->boolean('is_featured')]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:10', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'summary' => ['required', 'string', 'min:20', 'max:500'],
            'content' => ['required', 'string', 'min:50'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', Rule::in([Post::STATUS_DRAFT, Post::STATUS_PUBLISHED])],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'tiêu đề',
            'category_id' => 'chuyên mục',
            'summary' => 'tóm tắt',
            'content' => 'nội dung',
            'thumbnail' => 'ảnh đại diện bài viết',
            'status' => 'trạng thái',
            'published_at' => 'ngày đăng',
            'tags' => 'thẻ',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn chuyên mục.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ];
    }
}
