<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'          => 'Admin',
                'email'         => 'admin@gmail.com',
                'phone'         => '01700000000',
                'password'      => Hash::make('12345678'),
                'role_id'       => 1,
            ],
            [
                'name'          => 'User',
                'email'         => 'user@gmail.com',
                'phone'         => '01700000001',
                'password'      => Hash::make('12345678'),
                'role_id'       => 2,
            ],
        ]);
    }
}
