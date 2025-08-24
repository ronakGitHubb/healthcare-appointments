<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentService
{
    /**
     * Book an appointment for a user with a healthcare professional.
     *
     * @param  int     $userId         ID of the user booking the appointment
     * @param  int     $professionalId ID of the healthcare professional
     * @param  string  $startTime      Appointment start time
     * @param  string  $endTime        Appointment end time
     * @return array                   Status and appointment data or error message
     */
    public function bookAppointment($userId, $professionalId, $startTime, $endTime)
    {
        $professional = HealthcareProfessional::findOrFail($professionalId);

        $appointmentStart = Carbon::parse($startTime);
        $appointmentEnd = Carbon::parse($endTime);

        // Check working hours
        $workingStart = Carbon::parse($appointmentStart->toDateString() . ' ' . $professional->start_time);
        $workingEnd = Carbon::parse($appointmentStart->toDateString() . ' ' . $professional->end_time);

        if ($appointmentStart->lt($workingStart) || $appointmentEnd->gt($workingEnd)) {
            return [
                'status' => false,
                'message' => "Requested time is outside professional's working hours ({$professional->start_time} - {$professional->end_time})"
            ];
        }

        // Check professional availability
        $conflict = Appointment::where('healthcare_professional_id', $professionalId)
            ->where('status', 'booked')
            ->where(function ($q) use ($appointmentStart, $appointmentEnd) {
                $q->where('appointment_start_time', '<', $appointmentEnd)
                  ->where('appointment_end_time', '>', $appointmentStart);
            })
            ->exists();

        if ($conflict) {
            return ['status' => false, 'message' => 'Time slot not available'];
        }

        // Check user conflict
        $userConflict = Appointment::where('user_id', $userId)
            ->where('status', 'booked')
            ->where(function ($q) use ($appointmentStart, $appointmentEnd) {
                $q->where('appointment_start_time', '<', $appointmentEnd)
                  ->where('appointment_end_time', '>', $appointmentStart);
            })
            ->exists();

        if ($userConflict) {
            return ['status' => false, 'message' => "You've already another appointment between these times"];
        }

        // Create appointment
        $appointment = Appointment::create([
            'user_id' => $userId,
            'healthcare_professional_id' => $professionalId,
            'appointment_start_time' => $appointmentStart,
            'appointment_end_time' => $appointmentEnd,
        ]);

        return ['status' => true, 'appointment' => $appointment];
    }

    /**
     * Cancel an appointment for a given user
     *
     * @param int $appointmentId
     * @param int $userId
     * @return array
     */
    public function cancelAppointment(int $appointmentId, int $userId): array
    {
        try {
            $appointment = Appointment::where('id', $appointmentId)
                ->where('user_id', $userId)
                ->firstOrFail();

            $hoursDiff = now()->diffInHours(Carbon::parse($appointment->appointment_start_time), false);

            if ($hoursDiff < 0) {
                return [
                    'status' => false,
                    'message' => 'Cannot cancel past appointments',
                    'code' => 403
                ];
            }

            if ($hoursDiff < 24) {
                return [
                    'status' => false,
                    'message' => 'Cannot cancel within 24 hours',
                    'code' => 400
                ];
            }

            $appointment->update(['status' => 'cancelled']);

            return [
                'status' => true,
                'message' => 'Appointment cancelled',
                'code' => 200
            ];

        } catch (ModelNotFoundException $e) {
            return [
                'status' => false,
                'message' => 'Appointment not found',
                'code' => 404
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage() ?? 'Something went wrong',
                'code' => 500
            ];
        }
    }

    /**
     * Mark a specific appointment as completed.
     *
     * @param int $appointmentId The ID of the appointment
     * @param int $professionalId The ID of the professional completing the appointment
     * @return array
     */
    public function completeAppointment($appointmentId, $userId)
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            return ['status' => false, 'message' => 'Appointment not found.', 'code' => 404];
        }

        // Ensure the appointment belongs to the professional
        if ($appointment->user_id !== $userId) {
            return ['status' => false, 'message' => 'Unauthorized to complete this appointment.', 'code' => 403];
        }

        // Ensure the appointment is not already completed or cancelled
        if ($appointment->status === 'completed' || $appointment->status === 'cancelled') {
            return ['status' => false, 'message' => 'Appointment is already completed or cancelled.', 'code' => 409];
        }

        // Update the status and completed_at timestamp
        $appointment->status = 'completed';
        $appointment->completed_at = Carbon::now();
        $appointment->save();

        return ['status' => true, 'message' => 'Appointment marked as completed.', 'code' => 200];
    }

}
