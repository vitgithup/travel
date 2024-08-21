<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Flight;
use App\Models\Product;
use Illuminate\Database\Seeder;

// C:\wamp64\bin\php\php8.1.26\php.exe  artisan db:seed
// php artisan make:seeder UnitSeeder
// php artisan make:seeder UnitConvertSeeder
// php artisan make:seeder ProductCategorySeeder
// php artisan make:seeder ProductSeeder
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            FlightSeeder::class,
        ]);


    }
}
