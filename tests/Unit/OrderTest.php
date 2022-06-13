<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Type;

class OrderTest extends TestCase
{
    //use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_create_type()
    {
        $type = Type::factory(1)->create(
            ['name' => 'tipoteste']
        );

        $this->assertDatabaseHas('types', ['name' => 'tipoteste']);
    }
}
