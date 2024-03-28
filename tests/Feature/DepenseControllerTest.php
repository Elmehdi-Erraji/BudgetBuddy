<?php

namespace Tests\Feature;

use App\Models\Depense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepenseControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }



    public function testStore()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/depenses', [
            'title' => 'Test Depense',
            'description' => 'This is a test depense',
            'expense' => 50.25,
            'image' => 'test.jpg',
        ]);

        $response->assertStatus(201);

        $response->assertJson(['depense' => [
            'title' => 'Test Depense',
            'description' => 'This is a test depense',
            'expense' => 50.25,
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $depense = Depense::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->putJson("/api/depenses/{$depense->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'expense' => 75.50,
            'image' => 'updated.jpg',
        ]);

        $response->assertStatus(200);

        $depense->refresh();

        $this->assertEquals('Updated Title', $depense->title);
        $this->assertEquals('Updated description', $depense->description);
        $this->assertEquals(75.50, $depense->expense);
        $this->assertEquals('updated.jpg', $depense->image);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $depense = Depense::factory()->create(['user_id' => $user->id]);
    
        $response = $this->actingAs($user)->deleteJson("/api/depenses/{$depense->id}");
    
        $response->assertStatus(200);
    
        $this->assertDatabaseMissing('depenses', ['id' => $depense->id]);
    }
}
