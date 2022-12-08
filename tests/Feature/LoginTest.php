<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable;

//user can login with correct credentials

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
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
    /// Esse testnao funciona,ele retorna um status 404. No postman ele functiona e retorna 200 com json
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

        $this->assertGuest();
    }

    public function test_user_login_and_logout()
    {
        //$this->withoutMiddleware();
        $user = User::factory()->create([
            'email' => 'charles.regnier@example.com',
            'password' => '12345678'
        ]);
        $this->actingAs($user);
        $response->assertAuthenticatedAs($user);

        $this->post('/api/logout', [
            'email' => $user->email,
        ]);

        $this->assertGuest();
    }
}
