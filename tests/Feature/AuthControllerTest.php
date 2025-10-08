<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase; 

    /** @test */
    public function store_creates_user_with_valid_data()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function store_fails_with_invalid_data()
    {
        $payload = [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(400)
                 ->assertJsonStructure(['error']);
    }

    /** @test */
    public function login_returns_token_with_correct_credentials()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $payload = [
            'email' => 'john@example.com',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    /** @test */
    public function login_fails_with_wrong_credentials()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $payload = [
            'email' => 'john@example.com',
            'password' => 'wrongpass',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(422)
                 ->assertJsonStructure(['message', 'errors']);
    }

    /** @test */
    public function login_fails_with_nonexistent_user()
    {
        $payload = [
            'email' => 'nonexistent@example.com',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(422)
                 ->assertJsonStructure(['message', 'errors']);
    }
    
    /** @test */
public function token_allows_access_to_protected_routes()
{
    
    $user = User::create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $token = $user->createToken('api_token')->plainTextToken;

    $responseWithoutToken = $this->getJson('/api/tasks');
    $responseWithoutToken->assertStatus(401)
                         ->assertJson([
                            'message' => 'Unauthenticated.'
                         ]);


    $responseWithToken = $this->getJson('/api/tasks', [
        'Authorization' => 'Bearer '.$token
    ]);

    $responseWithToken->assertStatus(200)
                      ->assertJsonStructure(['tasks']);
}

}
