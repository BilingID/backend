<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    
    private $mockUserData = [
        'fullname' => 'Test User',
        'email' => 'test_user@example.com',
        'password' => 'test_password',
        'password_confirmation' => 'test_password',
    ];
    
    public function test_new_user_can_register(): void
    {
        $response = $this->post('/api/v1/auth/register', $this->mockUserData);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'User registered successfully',
        ]);
        // response has data key and token key inside
        $response->assertJsonStructure([
            'data' => [
                'token',
            ],
        ]);

        // check if user is created in database
        $this->assertDatabaseHas('users', [
            'fullname' => $this->mockUserData['fullname'],
            'email' => $this->mockUserData['email'],
        ]);
    }

    public function test_duplicated_user_register(): void
    {
        $response = $this->post('/api/v1/auth/register', $this->mockUserData);
        $response->assertStatus(200);
        
        $response = $this->post('/api/v1/auth/register', $this->mockUserData);
        $response->assertStatus(302);
    }

    public function test_user_with_valid_credential_can_login(): void
    {
        $response = $this->post('/api/v1/auth/register', $this->mockUserData);
        $response->assertStatus(200);
        
        $response = $this->post('/api/v1/auth/login', [
            'email' => $this->mockUserData['email'],
            'password' => $this->mockUserData['password'],
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'User login successfully',
        ]);
        $response->assertJsonStructure([
            'data' => [
                'token',
            ]
        ]);
    }
    
    public function test_user_with_invalid_credential_couldnt_login(): void
    {
        $response = $this->post('/api/v1/auth/register', $this->mockUserData);
        $response->assertStatus(200);
        
        $response = $this->post('/api/v1/auth/login', [
            'email' => $this->mockUserData['email'],
            'password' => fake()->password,
        ]);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Your email or password is incorrect',
        ]);
        
    }

    public function test_user_not_registedered_couldnt_login(): void
    {
        $response = $this->post('/api/v1/auth/login', [
            'email' => $this->mockUserData['email'],
            'password' => $this->mockUserData['password'],
        ]);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Your email or password is incorrect',
        ]);
        
    }

}
