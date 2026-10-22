<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    // Kho câu tiếng Việt để ghép thành đoạn văn mẫu
    private const SENTENCES = [
        'Theo ghi nhận của phóng viên, nhiều người dân bày tỏ sự quan tâm đặc biệt tới vấn đề này trong những ngày gần đây.',
        'Các chuyên gia cho rằng đây là tín hiệu tích cực, mở ra nhiều cơ hội mới trong thời gian tới.',
        'Đại diện đơn vị tổ chức cho biết mọi công tác chuẩn bị đã hoàn tất và sẵn sàng triển khai.',
        'Bên cạnh những kết quả đạt được, vẫn còn không ít khó khăn, thách thức cần được tháo gỡ kịp thời.',
        'Nhiều ý kiến đề xuất cần có cơ chế phối hợp chặt chẽ hơn giữa các bên liên quan.',
        'Số liệu thống kê cho thấy mức tăng trưởng ổn định so với cùng kỳ năm trước.',
        'Người tiêu dùng ngày càng chú trọng tới chất lượng và trải nghiệm thay vì chỉ quan tâm đến giá cả.',
        'Lãnh đạo địa phương khẳng định sẽ tiếp tục theo dõi sát sao và có giải pháp phù hợp.',
        'Đây được xem là bước đi quan trọng nhằm nâng cao hiệu quả và chất lượng phục vụ người dân.',
        'Sự kiện thu hút đông đảo người tham dự, tạo được nhiều dấu ấn tốt đẹp.',
        'Giới trẻ hưởng ứng nhiệt tình, chia sẻ rộng rãi thông tin trên các nền tảng mạng xã hội.',
        'Các doanh nghiệp đang tích cực chuyển đổi số để thích ứng với xu hướng thị trường.',
        'Kế hoạch được triển khai theo nhiều giai đoạn, ưu tiên những khu vực có nhu cầu cấp thiết.',
        'Ban tổ chức khuyến cáo người dân cập nhật thông tin từ các nguồn chính thống.',
        'Kết quả khảo sát mới nhất phản ánh mức độ hài lòng ngày càng cao của người dùng.',
        'Trong thời gian tới, đơn vị sẽ tiếp tục mở rộng quy mô và đa dạng hóa các hoạt động.',
        'Nhiều mô hình hay, cách làm sáng tạo đã được nhân rộng và mang lại hiệu quả thiết thực.',
        'Giới phân tích nhận định thị trường sẽ còn nhiều biến động trong những tháng cuối năm.',
    ];

    public function definition(): array
    {
        $title = Str::ucfirst(fake()->sentence(8));

        return [
            'category_id' => Category::factory(),
            'user_id' => User::factory()->admin(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(5),
            'summary' => $this->sentences(2),
            'content' => $this->paragraphs(fake()->numberBetween(4, 7)),
            'thumbnail' => 'https://picsum.photos/seed/'.Str::random(8).'/800/450',
            'status' => Post::STATUS_PUBLISHED,
            'is_featured' => false,
            'views' => fake()->numberBetween(20, 5000),
            'published_at' => fake()->dateTimeBetween('-60 days', 'now'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => Post::STATUS_DRAFT, 'published_at' => null]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }

    private function sentences(int $count): string
    {
        return implode(' ', fake()->randomElements(self::SENTENCES, $count));
    }

    private function paragraphs(int $count): string
    {
        $html = '';
        for ($i = 0; $i < $count; $i++) {
            $html .= '<p>'.$this->sentences(fake()->numberBetween(3, 5)).'</p>';
            if ($i === 1) {
                $html .= '<h3>'.fake()->randomElement(['Những điểm đáng chú ý', 'Ý kiến chuyên gia', 'Tác động tới người dân', 'Hướng đi trong thời gian tới']).'</h3>';
            }
        }

        return $html;
    }
}
