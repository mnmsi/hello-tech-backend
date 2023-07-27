<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('site_settings')->insert([
            [
                'name' => 'Hello Tech',
                'email' => 'sales@hellotech.com',
                'phone' => '+880 156893456',
                'header_logo' => '',
                'footer_logo' => '',
            ],
        ]);
    }
}
