<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class HealthcareProfessional extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'specialty','start_time','end_time'];

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
