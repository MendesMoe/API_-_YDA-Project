<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable;


class LoginTest extends TestCase
{
    //use RefreshDatabase;

    public function test_user_login_form()
    {
        //$this->withoutMiddleware();
        $user = User::factory()->create([
            'email' => 'charles.regnier@example.com',
            'password' => '12345678'
        ]);

        $response = $this->actingAs($user);

        $response->assertAuthenticatedAs($user);
    }

    public function test_user_login_with_wrong_password_size()
    {
        $this->post('/api/login', [
            'email' => 'testelogin@gmail.com',
            'password' => '123',
        ])->assertStatus(404);
    }

    public function test_user_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'charles@example.com',
            'password' => '12345678'
        ]);

        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword'
        ]);

        $this->assertInvalidCredentials(['email' => $user->email, 'password' => 'wrongpassword']);
    }
    /*
    public function test_user_can_logout()
    {
        //$this->withoutMiddleware();
        $user = User::factory()->create([
            'email' => 'charles.regnier@example.com',
            'password' => '12345678'
        ]);
        $response = $this->actingAs($user);
        $response = $this->assertAuthenticatedAs($user);

        $response = $this->post('/api/logout');

        $response->assertStatus(200)->assertJsonFragment([
            'message' => 'logout'
        ]);
    }*/
}
