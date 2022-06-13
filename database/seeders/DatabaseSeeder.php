<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Firm;
use App\Models\Type;
use App\Models\Service;
use App\Models\Product;
use App\Models\Order;
use App\Models\Odetail;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     */

    public function run()
    {
        $types = Type::factory(3)
            ->has(Service::factory()->count(3)
                ->has(Product::factory()->count(2)))
            ->create();

        $firms = Firm::factory(5)
            ->has(User::factory()->count(3)
                ->has(Order::factory()->count(2)
                    ->has(Odetail::factory()->count(2))))
            ->create();
    }
}
