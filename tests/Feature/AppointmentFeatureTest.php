<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use App\Models\User;
use Laravel\Passport\Passport;
use App\Services\AppointmentService;
use Mockery;
use Laravel\Passport\ClientRepository;

class AppointmentFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $appointmentService;

    public function setUp(): void
    {
        parent::setUp();

        // Use the Passport facade to create the client
        /* Passport::client()->create([
            'name' => 'Test Personal Access Client',
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => true,
            'password_client' => false,
            'revoked' => false,
        ]); */

        $this->appointmentService = Mockery::mock(AppointmentService::class);
        $this->app->instance(AppointmentService::class, $this->appointmentService);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test getting a list of all healthcare professionals.
     *
     * @return void
     */
    public function test_can_get_healthcare_professionals_list()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        HealthcareProfessional::factory()->count(3)->create();

        $response = $this->getJson('/api/professionals');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'result')
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'result' => [
                         '*' => [
                             'id', 'name', 'specialty'
                         ]
                     ]
                 ]);
    }

    /**
     * Test booking an appointment successfully.
     *
     * @return void
     */
    public function test_can_book_an_appointment_successfully()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $appointmentData = [
            'healthcare_professional_id' => 1,
            'appointment_start_time' => now()->addDay()->toDateTimeString(),
            'appointment_end_time' => now()->addDay()->addHour()->toDateTimeString()
        ];

        $this->appointmentService->shouldReceive('bookAppointment')
                                 ->once()
                                 ->with(
                                     $user->id,
                                     $appointmentData['healthcare_professional_id'],
                                     $appointmentData['appointment_start_time'],
                                     $appointmentData['appointment_end_time']
                                 )
                                 ->andReturn([
                                     'status' => true,
                                     'message' => 'Appointment booked successfully',
                                     'appointment' => (object)[]
                                 ]);

        $response = $this->postJson('/api/appointments', $appointmentData);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Appointment booked successfully'
                 ]);
    }

    /**
     * Test canceling an appointment successfully.
     *
     * @return void
     */
    public function test_can_cancel_an_appointment()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $appointment = Appointment::factory()->for($user)->create();

        $this->appointmentService->shouldReceive('cancelAppointment')
            ->once()
            ->with($appointment->id, $user->id)
            ->andReturn([
                'status' => true,
                'message' => 'Appointment canceled successfully.',
                'code' => 200
            ]);

        $response = $this->deleteJson("/api/appointments/{$appointment->id}");
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Appointment canceled successfully.'
            ]);
    }

    /**
     * Test getting a list of user's appointments.
     *
     * @return void
     */
    public function test_can_get_my_appointments()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        Appointment::factory()->count(2)->for($user)->create();

        $response = $this->getJson('/api/appointments');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'result')
            ->assertJsonStructure([
                'status',
                'message',
                'result' => [
                    '*' => [
                        'id', 'user_id', 'healthcare_professional_id', 'appointment_start_time', 'appointment_end_time'
                    ]
                ]
            ]);
    }
}
