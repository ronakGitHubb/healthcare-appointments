<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'healthcare_professional_id',
        'appointment_start_time',
        'appointment_end_time',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function healthcareProfessional()
    {
        return $this->belongsTo(HealthcareProfessional::class);
    }

    /**
     * Format dates in API responses.
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
