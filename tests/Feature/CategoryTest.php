<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    // user can create category with unique name
    public function test_user_can_create_unique_category()
    {
        $response = $this->actingAs($this->user)->post('/categories', [
            'name' => 'New Category',
        ]);
        $response->assertStatus(200);
    }

    // user cannot create duplicate category
    public function test_user_cannot_create_duplicate_category()
    {
        $response = $this->actingAs($this->user)->post('/categories', [
            'name' => $this->category->name,
        ]);
        $response->assertStatus(401);
    }

    // user can update with unique name
    public function test_user_can_update_with_unique_name()
    {
        $response = $this->actingAs($this->user)->patch('/categories/' . $this->category->id, [
            'name' => 'New Category',
        ]);
        $response->assertStatus(200);
    }

    // user cannot update category with duplicate name
    public function test_user_cannot_update_category_with_duplicate_name()
    {
        $newCategory = Category::factory()->create([
            'name' => 'New Category',
            'user_id' => $this->user->id
        ]);
        $response = $this->actingAs($this->user)->patch('/categories/' . $newCategory->id, [
            'name' => $this->category->name,
        ]);
        $response->assertStatus(401);
    }

    // user can only update their own category
    public function test_user_can_only_update_their_own_category()
    {
        $newUser = User::factory()->create();
        $response = $this->actingAs($newUser)->patch('/categories/' . $this->category->id, [
            'name' => 'New Category',
        ]);
        $response->assertStatus(404);
    }

    // user can delete their own category
    public function test_user_can_delete_their_own_category()
    {
        $response = $this->actingAs($this->user)->delete('/categories/' . $this->category->id);
        $response->assertStatus(200);
    }

    // user cannot delete other user's category
    public function test_user_cannot_delete_other_user_category()
    {
        $newUser = User::factory()->create();
        $response = $this->actingAs($newUser)->delete('/categories/' . $this->category->id);
        $response->assertStatus(404);
    }

    // guest cannot access categories
    public function test_guest_cannot_access_categories()
    {
        $response = $this->get('/categories');
        $response->assertStatus(302);
    }
}
