<?php

namespace Database\Seeders;

use App\Models\StoreLayout;
use Illuminate\Database\Seeder;

class StoreLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreLayout::factory()
            ->count(5)
            ->create();
    }
}
