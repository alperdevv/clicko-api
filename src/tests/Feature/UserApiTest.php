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
    }

    #[Test]
    public function it_can_get_all_users()
    {
            $this->actingAs($this->testUser);
            User::factory()->count(3)->create();
    
            $response = $this->getJson('/api/users');
    
            $response->assertStatus(200)
                    ->assertJsonStructure([
                        'data' => [
                            '*' => ['id', 'name', 'email', 'created_at', 'updated_at']
                        ]
                    ]);
    }
}
