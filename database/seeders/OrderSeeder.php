<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Firm;

use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory()
            ->count(5)
            ->trashed()
            ->for(Firm::factory())
            ->create();
    }
}
