<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

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

    // user can create new article
    public function test_user_can_create_new_article()
    {
        $response = $this->actingAs($this->user)->post('/articles', [
            'title' => 'New Article',
            'content' => 'New Article Body',
            'category_id' => $this->category->id
        ]);
        $response->assertStatus(200);
    }

    // user can update article
    public function test_user_can_update_article()
    {
        $response = $this->actingAs($this->user)->patch('/articles/' . $this->article->id, [
            'title' => 'New Article',
            'content' => 'New Article Body',
            'category_id' => $this->category->id
        ]);
        $response->assertStatus(200);
    }

    // user can only update its own article
    public function test_user_can_only_update_its_own_article()
    {
        $newUser = User::factory()->create();
        $response = $this->actingAs($newUser)->patch('/articles/' . $this->article->id, [
            'title' => 'New Article',
            'content' => 'New Article Body',
            'category_id' => $this->category->id
        ]);
        $response->assertStatus(404);
    }

    // user cannot update with invalid data
    public function test_user_cannot_update_with_invalid_data()
    {
        $response = $this->actingAs($this->user)->patch('/articles/' . $this->article->id, [
            'title' => '',
            'content' => 'New Article Body',
            'category_id' => $this->category->id
        ]);
        $response->assertStatus(401);
    }

    // user can delete article
    public function test_user_can_delete_article()
    {
        $response = $this->actingAs($this->user)->delete('/articles/' . $this->article->id);
        $response->assertStatus(200);
    }

    // user can only delete its own article
    public function test_user_can_only_delete_its_own_article()
    {
        $newUser = User::factory()->create();
        $response = $this->actingAs($newUser)->delete('/articles/' . $this->article->id);
        $response->assertStatus(404);
    }

    // guest cannot access articles
    public function test_guest_cannot_access_articles()
    {
        $response = $this->get('/articles');
        $response->assertStatus(302);
    }
}
