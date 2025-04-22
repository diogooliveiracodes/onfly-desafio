<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\TravelRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\TravelStatus;

class TravelRequestFactory extends Factory
{
    protected $model = TravelRequest::class;

    public function definition(): array
    {
        $departure = $this->faker->dateTimeBetween('+1 days', '+10 days');
        $return = (clone $departure)->modify('+'.rand(1, 7).' days');

        return [
            'user_id' => User::factory(),
            'requester_name' => $this->faker->name,
            'destination' => $this->faker->city,
            'departure_date' => $departure->format('Y-m-d'),
            'return_date' => $return->format('Y-m-d'),
            'status' => TravelStatus::SOLICITADO,
        ];
    }
}
