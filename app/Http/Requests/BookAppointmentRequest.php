<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages()
    {
        return [
            'healthcare_professional_id.required' => 'Healthcare professional is required',
            'healthcare_professional_id.exists' => 'Selected healthcare professional does not exist',
            'appointment_start_time.required' => 'Start time is required',
            'appointment_start_time.after' => 'Start time must be in the future',
            'appointment_end_time.required' => 'End time is required',
            'appointment_end_time.after' => 'End time must be after start time',
        ];
    }

    /**
     * Override failed validation response
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $errors[0] ?? 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
