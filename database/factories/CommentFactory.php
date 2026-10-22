<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    private const SAMPLES = [
        'Bài viết rất hay và bổ ích, cảm ơn tác giả!',
        'Thông tin cập nhật nhanh, mong báo tiếp tục theo dõi vụ việc.',
        'Mình nghĩ vấn đề này cần được nhìn nhận ở nhiều góc độ hơn.',
        'Đọc xong thấy rất tâm đắc, đã chia sẻ cho bạn bè cùng đọc.',
        'Hy vọng sẽ sớm có giải pháp triệt để cho vấn đề này.',
        'Số liệu trong bài có nguồn từ đâu vậy ạ?',
        'Rất mong có thêm nhiều bài phân tích chuyên sâu như thế này.',
        'Tin tốt quá, chúc mừng mọi người!',
        'Cảm ơn ban biên tập đã đưa tin kịp thời.',
        'Ý kiến của chuyên gia trong bài rất thuyết phục.',
    ];

    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'content' => fake()->randomElement(self::SAMPLES),
            'is_approved' => fake()->boolean(92),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
