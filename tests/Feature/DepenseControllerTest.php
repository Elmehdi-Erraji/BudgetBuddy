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
        // Create a user for testing
        $user = User::factory()->create();

        // Simulate an authenticated request to the store endpoint
        $response = $this->actingAs($user)->postJson('/api/depenses', [
            'title' => 'Test Depense',
            'description' => 'This is a test depense',
            'expense' => 50.25,
            'image' => 'test.jpg',
        ]);

        // Assert that the response status is 201 Created
        $response->assertStatus(201);

        // Assert that the response contains the created depense
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
        // Create a user and a depense for testing
        $user = User::factory()->create();
        $depense = Depense::factory()->create(['user_id' => $user->id]);

        // Simulate an authenticated request to update the depense
        $response = $this->actingAs($user)->putJson("/api/depenses/{$depense->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'expense' => 75.50,
            'image' => 'updated.jpg',
        ]);

        // Assert that the response status is 200 OK
        $response->assertStatus(200);

        // Refresh the depense instance to get the updated data from the database
        $depense->refresh();

        // Assert that the depense has been updated with the new data
        $this->assertEquals('Updated Title', $depense->title);
        $this->assertEquals('Updated description', $depense->description);
        $this->assertEquals(75.50, $depense->expense);
        $this->assertEquals('updated.jpg', $depense->image);
    }

    public function testDestroy()
    {
        // Create a user and a depense for testing
        $user = User::factory()->create();
        $depense = Depense::factory()->create(['user_id' => $user->id]);
    
        // Simulate an authenticated request to delete the depense
        $response = $this->actingAs($user)->deleteJson("/api/depenses/{$depense->id}");
    
        // Assert that the response status is 200 OK
        $response->assertStatus(200);
    
        // Assert that the depense has been deleted from the database
        $this->assertDatabaseMissing('depenses', ['id' => $depense->id]);
    }
}
