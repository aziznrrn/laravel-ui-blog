<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // create user and article and category first
    public function setUp():void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create([
            'user_id' => $this->user->id
        ]);
        $this->article = Article::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);
    }

    // guest cannot access /home page
    public function test_guest_cannot_access_home_page()
    {
        $response = $this->get('/home');
        $response->assertRedirect('/login');
    }

    // guest can access posts page
    public function test_guest_can_access_posts_page()
    {
        $response = $this->get('/posts');
        $response->assertStatus(200);
    }

    // guest redirect to /posts when try to access / page
    public function test_guest_redirect_to_posts_page_when_try_to_access_home_page()
    {
        $response = $this->get('/');
        $response->assertRedirect('/posts');
    }

    // guest can access register page
    public function test_guest_can_access_register_page()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    // guest can access posts/{post} page
    public function test_guest_can_access_posts_show_page()
    {
        $response = $this->get('/posts/' . $this->article->id);
        $response->assertStatus(200);
    }

    // guest can access login page
    public function test_guest_can_access_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    // guest can access posts with specific category
    public function test_guest_can_access_posts_with_specific_category()
    {
        $response = $this->get('/posts?category=' . $this->category->id);
        $response->assertStatus(200);
    }
}
