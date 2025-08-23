<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class HealthcareProfessional extends Model
{
    protected $fillable = ['name', 'specialty'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Format dates in API responses.
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
