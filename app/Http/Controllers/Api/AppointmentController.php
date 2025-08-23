<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\BookAppointmentRequest;
use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use Illuminate\Http\Request;
use App\Services\AppointmentService;

class AppointmentController extends Controller
{
    protected $appointmentService;

    /**
     * AppointmentController constructor.
     *
     * @param  AppointmentService  $appointmentService  Injected service for handling appointment logic
     */
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Get a list of all healthcare professionals.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function professionals()
    {
        $professionals = HealthcareProfessional::all();
        return response()->json(['status' => true, 'message' => "Healthcare Professionals List", 'result' => $professionals]);
    }

    /**
     * Book an appointment for the authenticated user.
     *
     * @param  \App\Http\Requests\BookAppointmentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function book(BookAppointmentRequest $request)
    {
        try {
            $result = $this->appointmentService->bookAppointment(
                auth()->id(),
                $request->healthcare_professional_id,
                $request->appointment_start_time,
                $request->appointment_end_time
            );

            if (!$result['status']) {
                return response()->json(['status' => false, 'message' => $result['message']], 409);
            }

            return response()->json([
                'status' => true,
                'message' => "Appointment booked successfully",
                'result' => $result['appointment']
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage() ?? 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Cancel a user's appointment.
     *
     * @param  int  $id  The ID of the appointment to cancel
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        $response = $this->appointmentService->cancelAppointment($id, auth()->id());

        return response()->json([
            'status' => $response['status'],
            'message' => $response['message']
        ], $response['code']);
    }

    /**
     * Get all appointments for the currently authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myAppointments()
    {
        $appointments = auth()->user()->appointments()->with('healthcareProfessional')->get();
        return response()->json(['status' => true, 'message' => "Appointment List", 'result' => $appointments]);
    }
}
