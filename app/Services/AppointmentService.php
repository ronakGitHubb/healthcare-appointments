<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use Carbon\Carbon;

class AppointmentService
{
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
}
