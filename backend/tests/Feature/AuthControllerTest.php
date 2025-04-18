<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
            'device_name' => 'TestDevice',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'device_name' => 'TestDevice',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNAUTHORIZED)
            ->assertJson([
                'message' => 'The provided credentials are incorrect.'
            ]);
    }


    /** @test */
    public function login_requires_email_password_and_device_name()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email', 'password', 'device_name']);
    }
}
