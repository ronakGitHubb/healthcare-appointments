<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'healthcare_professional_id' => 1, // HealthcareProfessional::factory(),
            'appointment_start_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'appointment_end_time' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
        ];
    }
}
