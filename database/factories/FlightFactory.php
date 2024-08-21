<?php

namespace Database\Factories;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class FlightFactory extends Factory
{
    protected $model = Flight::class;

    public function definition()
    {
        $departureTime = $this->faker->dateTimeBetween('+1 week', '+1 year');
        $arrivalTime = (new Carbon($departureTime))->addHours($this->faker->numberBetween(1, 12));

        return [
            'flight_number' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{3,4}'),
            'departure_airport' => $this->faker->regexify('[A-D]{3}'),
            'arrival_airport' => $this->faker->regexify('[A-D]{3}'),
            'departure_time' => $departureTime,
            'arrival_time' => $arrivalTime,
            'available_seats' => $this->faker->numberBetween(0, 300),
            'price' => $this->faker->randomFloat(2, 50, 1000),
        ];
    }
}
