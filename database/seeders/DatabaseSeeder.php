<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\PaymentMethod;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Item::factory(25)->create();
        Inventory::factory(25)->create();
        Customer::factory(20)->create();
        PaymentMethod::factory(3)->create();
        

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
