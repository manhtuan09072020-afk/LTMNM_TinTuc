<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render(): void
    {
        $this->seed();
        $post = Post::published()->first();

        $this->get('/')->assertOk()->assertSee('Tin mới nhất');
        $this->get(route('posts.show', $post))->assertOk()->assertSee($post->title);
        $this->get(route('categories.show', $post->category))->assertOk();
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }
}
