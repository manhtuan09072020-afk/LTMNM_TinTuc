<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tài khoản demo
        $admin = User::factory()->admin()->create([
            'name' => 'Quản trị viên',
            'email' => 'admin@tintuc.test',
            'password' => 'Admin@123',
        ]);

        $editor = User::factory()->admin()->create([
            'name' => 'Biên tập viên',
            'email' => 'editor@tintuc.test',
            'password' => 'Admin@123',
        ]);

        User::factory()->create([
            'name' => 'Nguyễn Văn An',
            'email' => 'user@tintuc.test',
            'password' => 'User@123',
        ]);

        $readers = User::factory(12)->create()->push(User::where('email', 'user@tintuc.test')->first());

        // 2. Chuyên mục
        $categories = collect([
            ['Thời sự', '#dc3545', 'Tin tức thời sự trong nước nóng hổi, cập nhật liên tục.'],
            ['Thế giới', '#0d6efd', 'Diễn biến nổi bật của tình hình quốc tế.'],
            ['Kinh doanh', '#198754', 'Thị trường, doanh nghiệp, tài chính và đầu tư.'],
            ['Công nghệ', '#6f42c1', 'Xu hướng công nghệ, thiết bị số và chuyển đổi số.'],
            ['Thể thao', '#fd7e14', 'Bóng đá, các giải đấu và gương mặt thể thao.'],
            ['Giải trí', '#d63384', 'Âm nhạc, điện ảnh và đời sống nghệ sĩ.'],
            ['Sức khỏe', '#20c997', 'Kiến thức y tế, dinh dưỡng và chăm sóc sức khỏe.'],
            ['Giáo dục', '#0dcaf0', 'Tuyển sinh, du học và câu chuyện học đường.'],
        ])->map(fn ($c) => Category::create([
            'name' => $c[0],
            'slug' => Str::slug($c[0]),
            'color' => $c[1],
            'description' => $c[2],
        ]));

        // 3. Thẻ
        $tags = collect(['Nổi bật', 'Phân tích', 'Phỏng vấn', 'Video', 'Infographic', 'Đời sống', 'Chuyển đổi số', 'AI', 'Bóng đá', 'Chứng khoán', 'Du lịch', 'Tuyển sinh'])
            ->map(fn ($name) => Tag::create(['name' => $name, 'slug' => Str::slug($name)]));

        // 4. Bài viết: tiêu đề mẫu theo từng chuyên mục
        $titles = [
            'Thời sự' => [
                'Thành phố triển khai nhiều giải pháp giảm ùn tắc giao thông giờ cao điểm',
                'Hàng nghìn tình nguyện viên tham gia chiến dịch làm sạch bờ biển',
                'Mở rộng mô hình dịch vụ công trực tuyến toàn trình cho người dân',
                'Nắng nóng kéo dài, ngành điện khuyến cáo sử dụng điện tiết kiệm',
                'Khánh thành cây cầu mới nối liền hai bờ sông sau hai năm thi công',
                'Người dân hào hứng với tuyến xe buýt điện đầu tiên của thành phố',
            ],
            'Thế giới' => [
                'Hội nghị thượng đỉnh về khí hậu đạt được nhiều cam kết quan trọng',
                'Các nước châu Á đẩy mạnh hợp tác phát triển năng lượng tái tạo',
                'Liên hợp quốc kêu gọi tăng cường hỗ trợ nhân đạo cho vùng bị thiên tai',
                'Giá lương thực thế giới có xu hướng hạ nhiệt trong quý này',
                'Thành phố cổ châu Âu đón lượng khách du lịch kỷ lục mùa hè',
                'Hợp tác không gian quốc tế bước sang giai đoạn mới',
            ],
            'Kinh doanh' => [
                'Doanh nghiệp nhỏ và vừa tăng tốc chuyển đổi số để giữ chân khách hàng',
                'Thị trường chứng khoán khởi sắc nhờ dòng tiền từ nhà đầu tư cá nhân',
                'Xuất khẩu nông sản đạt mức tăng trưởng ấn tượng trong 9 tháng',
                'Thương mại điện tử tiếp tục là động lực tăng trưởng bán lẻ',
                'Các ngân hàng đồng loạt điều chỉnh lãi suất tiết kiệm',
                'Khởi nghiệp xanh thu hút sự quan tâm của các quỹ đầu tư',
            ],
            'Công nghệ' => [
                'Trí tuệ nhân tạo thay đổi cách chúng ta làm việc như thế nào',
                'Năm mẹo bảo vệ tài khoản mạng xã hội trước kẻ gian',
                'Điện thoại gập ngày càng phổ biến với người dùng trẻ',
                'Sinh viên Việt Nam giành giải cao tại cuộc thi lập trình quốc tế',
                'Laravel và hệ sinh thái PHP vẫn được ưa chuộng trong phát triển web',
                'Mạng 5G phủ sóng rộng hơn, tốc độ truy cập cải thiện rõ rệt',
            ],
            'Thể thao' => [
                'Đội tuyển quốc gia công bố danh sách tập trung chuẩn bị cho giải khu vực',
                'Giải chạy marathon thành phố thu hút hơn mười nghìn vận động viên',
                'Cầu thủ trẻ gây ấn tượng mạnh trong trận ra mắt đội một',
                'Bóng chuyền nữ giành chiến thắng thuyết phục ở vòng bảng',
                'Thể thao điện tử ngày càng được đầu tư bài bản',
                'Huấn luyện viên chia sẻ bí quyết xây dựng lối chơi tập thể',
            ],
            'Giải trí' => [
                'Bộ phim Việt cán mốc doanh thu ấn tượng sau tuần đầu công chiếu',
                'Liveshow âm nhạc ngoài trời quy tụ nhiều ca sĩ trẻ được yêu thích',
                'Lễ hội ẩm thực đường phố trở lại với nhiều món ăn đặc sắc',
                'Triển lãm nghệ thuật đương đại thu hút đông đảo giới trẻ',
                'Chương trình truyền hình thực tế mới gây sốt mạng xã hội',
                'Nghệ sĩ trẻ mang âm nhạc truyền thống đến gần hơn với khán giả',
            ],
            'Sức khỏe' => [
                'Bác sĩ khuyến cáo cách phòng bệnh hô hấp khi thời tiết giao mùa',
                'Thói quen ngủ đủ giấc giúp cải thiện trí nhớ và tâm trạng',
                'Chế độ ăn nhiều rau xanh giúp giảm nguy cơ bệnh tim mạch',
                'Đi bộ mỗi ngày ba mươi phút mang lại lợi ích gì cho cơ thể',
                'Hiến máu tình nguyện: nghĩa cử cao đẹp cứu sống nhiều người',
                'Chăm sóc sức khỏe tinh thần cho học sinh mùa thi cử',
            ],
            'Giáo dục' => [
                'Nhiều trường đại học công bố phương án tuyển sinh năm mới',
                'Học sinh hào hứng với mô hình lớp học trải nghiệm thực tế',
                'Bí quyết ôn thi hiệu quả từ các thủ khoa',
                'Chương trình học bổng hỗ trợ sinh viên có hoàn cảnh khó khăn',
                'Ứng dụng công nghệ trong dạy học giúp tiết học sinh động hơn',
                'Kỹ năng mềm ngày càng được nhà tuyển dụng đánh giá cao',
            ],
        ];

        foreach ($categories as $category) {
            foreach ($titles[$category->name] as $i => $title) {
                $post = Post::factory()
                    ->for($category)
                    ->for($i % 2 ? $editor : $admin, 'author')
                    ->state([
                        'title' => $title,
                        'slug' => Str::slug($title),
                        'is_featured' => $i === 0 && in_array($category->name, ['Thời sự', 'Công nghệ', 'Thể thao']),
                    ])
                    ->when($i === 5 && $category->name === 'Giáo dục', fn ($f) => $f->draft())
                    ->create();

                $post->tags()->attach($tags->random(rand(1, 3))->pluck('id'));

                Comment::factory(rand(0, 5))->for($post)->sequence(fn () => ['user_id' => $readers->random()->id])->create();
            }
        }
    }
}
