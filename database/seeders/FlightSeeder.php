<?php

namespace Database\Seeders;

use App\Models\Flight;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// C:\wamp64\bin\php\php8.1.26\php.exe  artisan db:seed --class=FlightSeeder
class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $flights = Flight::factory()->count(500)->create();
    }
}
