<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
        /** @test */
        public function a_user_can_login_with_valid_credentials()
        {
            // Create a user
            $user = User::factory()->create([
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
            ]);
    
            // Attempt login
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'user@example.com',
                'password' => 'password123',
            ]);
    
            // Assert response
            $response->assertStatus(200)
                ->assertJsonStructure(['access_token']);
        }
    
        /** @test */
        public function login_fails_with_invalid_credentials()
        {
            // Create a user
            $user = User::factory()->create([
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
            ]);
    
            // Attempt login with wrong password
            $response = $this->postJson('/api/v1/login', [
                'email' => 'user@example.com',
                'password' => 'wrongpassword',
            ]);
    
            // Assert response
            $response->assertStatus(401)
                ->assertJson(['message' => 'Invalid credentials']);
        }
    
        /** @test */
        public function only_admin_can_access_protected_routes()
        {
            // Create admin role
            $adminRole = Role::factory()->create(['name' => 'admin']);
    
            // Create an admin user and assign the role
            $admin = User::factory()->create([
                'password' => Hash::make('adminpassword'),
            ]);
            $admin->roles()->attach($adminRole);
    
            // Authenticate as admin
            Sanctum::actingAs($admin);
    
            // Attempt to access a protected route
            $response = $this->getJson('/api/v1/admin/dashboard');
    
            // Assert response
            $response->assertStatus(200);
        }
    
        /** @test */
        public function non_admin_users_cannot_access_protected_routes()
        {
            // Create a normal user
            $user = User::factory()->create([
                'password' => Hash::make('userpassword'),
            ]);
    
            // Authenticate as normal user
            Sanctum::actingAs($user);
    
            // Attempt to access admin route
            $response = $this->getJson('/api/v1/admin/travel');
    
            // Assert forbidden response
            $response->assertStatus(403);
        }
    
}
