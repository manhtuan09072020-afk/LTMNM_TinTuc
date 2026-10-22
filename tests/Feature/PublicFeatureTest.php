<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_by_keyword_and_category_keeps_query_on_pagination(): void
    {
        $sport = Category::factory()->create();
        Post::factory(10)->for($sport)->create(['title' => 'Đội tuyển bóng đá giành chiến thắng']);
        Post::factory(3)->create(['title' => 'Giá vàng tăng mạnh']);

        $this->get(route('search', ['q' => 'bóng đá', 'category' => $sport->id]))
            ->assertOk()
            ->assertSee('Tìm thấy')
            ->assertSee('<strong class="text-dark">10</strong>', false)
            ->assertSee('page=2', false)
            ->assertSee('category='.$sport->id, false);
    }

    public function test_draft_post_is_hidden_from_public(): void
    {
        $draft = Post::factory()->draft()->create();

        $this->get(route('posts.show', $draft))->assertNotFound();
        $this->get(route('search', ['q' => $draft->title]))
            ->assertSee('Tìm thấy <strong class="text-dark">0</strong>', false);
    }

    public function test_viewing_post_increments_views(): void
    {
        $post = Post::factory()->create(['views' => 5]);

        $this->get(route('posts.show', $post))->assertOk();
        $this->assertSame(6, $post->fresh()->views);
    }

    public function test_guest_cannot_comment(): void
    {
        $post = Post::factory()->create();

        $this->post(route('comments.store', $post), ['content' => 'Xin chào'])->assertRedirect(route('login'));
    }

    public function test_user_can_comment_with_ajax_and_validation_works(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('comments.store', $post), ['content' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors('content');

        $this->actingAs($user)
            ->postJson(route('comments.store', $post), ['content' => 'Bài viết rất hay!'])
            ->assertCreated()
            ->assertJsonPath('message', 'Đã gửi bình luận.')
            ->assertJsonStructure(['html']);

        $this->assertDatabaseHas('comments', ['post_id' => $post->id, 'user_id' => $user->id]);
    }

    public function test_user_can_only_delete_own_comment(): void
    {
        $comment = Comment::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($other)->deleteJson(route('comments.destroy', $comment))->assertForbidden();
        $this->actingAs($comment->user)->deleteJson(route('comments.destroy', $comment))->assertOk();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_hidden_comment_is_not_shown(): void
    {
        $comment = Comment::factory()->create(['is_approved' => false, 'content' => 'Bình luận vi phạm']);

        $this->get(route('posts.show', $comment->post))->assertDontSee('Bình luận vi phạm');
    }

    public function test_live_search_suggest_returns_json(): void
    {
        Post::factory()->create(['title' => 'Laravel 13 có gì mới']);

        $this->getJson(route('search.suggest', ['q' => 'Laravel']))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.title', 'Laravel 13 có gì mới');
    }
}
