<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Type;
use App\Models\Product;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function delete_type_service_product()
    {
        $type = Type::factory(1)
            ->has(Service::factory()->count(1)
                ->has(Product::factory()->count(1), [
                    'status' => 'inactif'
                ]))
            ->create();
    }
}
