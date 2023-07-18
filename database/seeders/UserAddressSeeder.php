<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_addresses')->insert([
            [
                'user_id'     => 1,
                'name'        => 'Md. Shohel Rana',
                'phone'       => '01700000000',
                'address'     => 'Dhaka, Bangladesh',
                'division_id' => 1,
                'city_id'     => 1,
                'area_id'     => 1,
                'is_default'  => 1,
            ],
            [
                'user_id'     => 2,
                'name'        => 'Md. Shohel Rana',
                'phone'       => '01700000000',
                'address'     => 'Dhaka, Bangladesh',
                'division_id' => 1,
                'city_id'     => 1,
                'area_id'     => 1,
                'is_default'  => 1,
            ],
        ]);
    }
}
