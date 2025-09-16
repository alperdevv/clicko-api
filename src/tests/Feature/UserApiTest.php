<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class UserApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->testUser = User::factory()->create([
            'name' => 'Second Test User',
            'email' => 'secondtest@example.com',
            'password' => Hash::make('123456'),
        ]);

        $this->actingAs($this->testUser);
    }

    #[Test]
    public function it_can_get_all_users()
    {
            User::factory()->count(3)->create();
    
            $response = $this->getJson('/api/users');
    
            $response->assertStatus(200)
                    ->assertJsonStructure([
                        'data' => [
                            '*' => ['id', 'name', 'email', 'created_at', 'updated_at']
                        ]
                    ]);
    }

    #[Test]    
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'New Testing User',
            'email' => 'newtestinguser@example.com',
            'password' => '123456'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201);
    }

    #[Test]
    public function it_can_show_a_user()
    {
    $user = User::factory()->create();

    $response = $this->getJson("/api/users/{$user->id}");

    $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
    }

    #[Test]
    public function it_can_update_a_user()
    {
    $user = User::factory()->create();
    
    $updateData = [
        'name' => 'New Updated Name',
        'email' => 'newupdated@example.com'
    ];

    $response = $this->putJson("/api/users/{$user->id}", $updateData);

    $response->assertStatus(200);
    
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Updated Name',
        'email' => 'newupdated@example.com'
    ]);
    }

    #[Test]
    public function it_can_delete_a_user()
    {
    $user = User::factory()->create();

    $response = $this->deleteJson("/api/users/{$user->id}");

    $response->assertStatus(204); 

    // Verificar que se eliminó de la DB
    $this->assertDatabaseMissing('users', [
        'id' => $user->id
    ]);
    }

}
