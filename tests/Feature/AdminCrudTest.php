<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_category_crud(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), ['name' => 'Du lịch', 'color' => '#123456', 'is_active' => 1])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $category = Category::where('slug', 'du-lich')->firstOrFail();

        $this->actingAs($this->admin)->get(route('admin.categories.show', $category))->assertOk()->assertSee('Du lịch');

        $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $category), ['name' => 'Du lịch - Khám phá', 'color' => '#654321'])
            ->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'slug' => 'du-lich-kham-pha', 'is_active' => false]);

        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category->fresh()))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_validation(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), ['name' => '', 'color' => 'do'])
            ->assertSessionHasErrors(['name', 'color']);
    }

    public function test_cannot_delete_category_with_posts(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $post->category))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $post->category_id]);
    }

    public function test_post_crud_with_upload_tags_and_soft_delete(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $tags = Tag::factory(2)->create();

        $this->actingAs($this->admin)->post(route('admin.posts.store'), [
            'title' => 'Bài viết thử nghiệm chức năng thêm mới',
            'category_id' => $category->id,
            'summary' => 'Tóm tắt bài viết thử nghiệm đủ dài để hợp lệ.',
            'content' => '<p>'.str_repeat('Nội dung bài viết thử nghiệm. ', 5).'</p>',
            'status' => 'published',
            'thumbnail' => UploadedFile::fake()->image('anh.jpg'),
            'tags' => $tags->pluck('id')->all(),
        ])->assertRedirect(route('admin.posts.index'))->assertSessionHas('success');

        $post = Post::where('title', 'Bài viết thử nghiệm chức năng thêm mới')->firstOrFail();
        $this->assertSame($this->admin->id, $post->user_id);
        $this->assertNotNull($post->published_at);
        $this->assertCount(2, $post->tags);
        Storage::disk('public')->assertExists($post->thumbnail);

        $this->actingAs($this->admin)->put(route('admin.posts.update', $post), [
            'title' => 'Bài viết thử nghiệm đã được chỉnh sửa',
            'category_id' => $category->id,
            'summary' => $post->summary,
            'content' => $post->content,
            'status' => 'draft',
            'tags' => [$tags[0]->id],
        ])->assertSessionHas('success');

        $post->refresh();
        $this->assertSame('bai-viet-thu-nghiem-da-duoc-chinh-sua', $post->slug);
        $this->assertSame('draft', $post->status);
        $this->assertCount(1, $post->tags);

        // Xóa mềm -> khôi phục -> xóa vĩnh viễn
        $this->actingAs($this->admin)->delete(route('admin.posts.destroy', $post))->assertSessionHas('success');
        $this->assertSoftDeleted($post);

        $this->actingAs($this->admin)->patch(route('admin.posts.restore', $post))->assertSessionHas('success');
        $this->assertNotSoftDeleted($post);

        $this->actingAs($this->admin)->delete(route('admin.posts.destroy', $post));
        $this->actingAs($this->admin)->delete(route('admin.posts.force-delete', $post))->assertSessionHas('success');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        Storage::disk('public')->assertMissing($post->thumbnail);
    }

    public function test_post_validation(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), ['title' => 'ngắn', 'status' => 'abc'])
            ->assertSessionHasErrors(['title', 'category_id', 'summary', 'content', 'status']);
    }

    public function test_admin_search_keeps_query_string_in_pagination(): void
    {
        Post::factory(15)->for(Category::factory())->create(['title' => 'Tin bóng đá hấp dẫn hôm nay']);

        $this->actingAs($this->admin)
            ->get(route('admin.posts.index', ['q' => 'bóng đá']))
            ->assertOk()
            ->assertSee('q=b%C3%B3ng%20%C4%91%C3%A1&amp;page=2', false);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->admin))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_user_crud(): void
    {
        $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Lê Văn C',
            'email' => 'c@example.com',
            'role' => 'user',
            'password' => 'matkhau123',
            'password_confirmation' => 'matkhau123',
        ])->assertSessionHas('success');

        $user = User::where('email', 'c@example.com')->firstOrFail();

        $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'name' => 'Lê Văn C',
            'email' => 'c@example.com',
            'role' => 'admin',
        ])->assertSessionHas('success');
        $this->assertTrue($user->fresh()->isAdmin());

        $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user))->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
