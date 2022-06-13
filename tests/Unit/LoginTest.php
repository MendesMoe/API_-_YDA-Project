<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

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
        ]); //[ 'email' => 'mcolas@example.com', 'password' => bcrypt('12345678')]
        //dd($user);
        $response = $this->actingAs($user);

        //$response = $this->post('/connexion', );
        $response->assertAuthenticatedAs($user);
        //$response->assertStatus(200);

        //assertRedirect('/settings');

        // $this->assertFalse(Auth::check());

        // $this->assertTrue($user);

        // $this->visit('/connexion')
        //     ->submitForm('Connexion', [
        //         'email' => $user->email,
        //         'password' => '12345678',
        //     ])
        //     ->seePageIs('/dashboard');

        //$response = $this->post('/connexion');
        //$response->assertStatus(200);

        // $response = $this->withHeaders([
        //     "Content-Type" => "application/json",
        //     'Accept' => "application/json",
        // ])->post('/connexion', ['email' => 'mcolas@example.com', 'password' => '12345678']);

        // $response->assertStatus(200);
        // $response->assertStatus(200);
        //$response->assertViewIs('auth.login');
    }
}
