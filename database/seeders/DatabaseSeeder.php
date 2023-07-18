<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountySeeder::class,
            DivisionSeeder::class,
            CitySeeder::class,
            AreaSeeder::class,
//            BannerSeeder::class,
//            BikeBodyTypeSeeder::class,
//            BrandSeeder::class,
//            CategorySeeder::class,
//            DeliveryOptionSeeder::class,
//            PaymentMethodSeeder::class,
//            ShowroomSeeder::class,
//            TestimonialSeeder::class,
            UserSeeder::class,
//            UserAddressSeeder::class,
//            ProductSeeder::class,
//            ProductColorSeeder::class,
//            ProductMediaSeeder::class,
//            ProductSpecificationSeeder::class,
            UserRoleSeeder::class
        ]);
    }
}
