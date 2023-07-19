<?php

namespace Database\Seeders;

use App\Models\System\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('delivery_options')->insert([
            [
                'name'  => 'Inside Dhaka',
                'bonus' => null,
            ],
            [
                'name'  => 'Outside Dhaka',
                'bonus' => null
            ]
        ]);
    }
}
