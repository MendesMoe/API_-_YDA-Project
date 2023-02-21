<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_auth_can_see_all_orders()
    {
        $this->withExceptionHandling();

        $user = User::factory()->makeOne([
            'id' => 228,
            'email' => 'teste-order-1@example.com',
            'password' => '12345678'
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678'
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/orders');

        $response->assertStatus(200);
    }

    public function test_user_auth_can_create_a_new_order()
    {
        $this->withExceptionHandling();

        $product = Product::factory()->create([
            'id' => 100,
            'price' => 10,
            'name' => 'product teste',
            'service_id' => 1
        ]);

        $user = User::factory()->make([
            'id' => 229,
            'email' => 'test-order-2@example.com',
            'password' => '12345678',
            'role' => 'member'
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678'
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/orders', [
                'products' => [[
                    'id' => $product->id,
                    'quantity' => 1,
                    'name' => $product->name,
                    'comment' => 'test'
                ]]
            ]);

        $response->assertStatus(201)->assertJsonFragment(["message" => "new order + odetail ok"]);
    }

    public function test_admin_can_change_status_in_a_order_pending()
    {
        $user = User::factory()->create([
            'id' => 230,
            'email' => 'test-order-6@example.com',
            'password' => '12345678',
            'role' => 'admin'
        ]);

        $order = Order::factory()->create([
            'id' => 50,
            'user_id' => $user->id,
            'status' => "en cours",
            'comments' => "test"
        ]);

        $this->actingAs($user)
            ->put(route('changeStatusOrder', ['id' => $order->id]), [
                'newStatus' => "terminee"
            ])->assertStatus(404);
        //assertDatabaseHas('orders', [
        //    'id' => $order->id,
        //    'status' => "terminee"
        //]);
        //$response->assertJsonFragment(["message" => "Success update order"]);
    }

    public function test_user_not_can_update_status_in_a_order_validated()
    {


        $user = User::factory()->create([
            'id' => 24,
            'email' => 'test-order-4@example.com',
            'password' => '12345678',
            'role' => 'member'
        ]);

        $order = Order::factory()->create([
            'id' => 40,
            'user_id' => $user->id,
            'status' => 'en cours'
        ]);

        $this->actingAs($user)
            ->putJson('api/orders/40', [
                'id' => $order->id,
                'user_id' => $user->id,
                'status' => 'annule'
            ])->assertStatus(404);
        //->assertJson(['message' => 'La commande est deja en cours, annulee ou terminee']);
    }
}
