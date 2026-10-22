<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_normal_user_gets_403_in_admin_area(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/posts')
            ->assertForbidden();
    }

    public function test_admin_can_open_every_admin_page(): void
    {
        $this->seed();
        $admin = User::where('email', 'admin@tintuc.test')->first();

        foreach (['/admin', '/admin/posts', '/admin/posts/create', '/admin/posts/trash', '/admin/categories',
            '/admin/categories/create', '/admin/tags', '/admin/comments', '/admin/users', '/admin/users/create'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_user_can_register_login_and_logout(): void
    {
        $this->post(route('register'), [
            'name' => 'Trần Thị B',
            'email' => 'b@example.com',
            'password' => 'matkhau123',
            'password_confirmation' => 'matkhau123',
        ])->assertRedirect(route('home'))->assertSessionHas('success');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'b@example.com', 'role' => 'user']);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();

        $this->post(route('login'), ['email' => 'b@example.com', 'password' => 'sai-mat-khau'])
            ->assertSessionHasErrors('email');

        $this->post(route('login'), ['email' => 'b@example.com', 'password' => 'matkhau123'])
            ->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }

    public function test_register_validation_messages_are_vietnamese(): void
    {
        $this->post(route('register'), ['name' => '', 'email' => 'khong-hop-le', 'password' => '123'])
            ->assertSessionHasErrors([
                'name' => 'Vui lòng nhập họ tên.',
                'email' => 'Trường email phải là địa chỉ email hợp lệ.',
            ]);
    }
}
