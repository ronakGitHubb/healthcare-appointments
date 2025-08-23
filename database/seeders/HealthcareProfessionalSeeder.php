<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HealthcareProfessional;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HealthcareProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table
        DB::table('healthcare_professionals')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert fresh records
        $now = Carbon::now();

        // Insert fresh records with timestamps
        HealthcareProfessional::insert([
        [
            'name'           => 'Dr. John Smith',
            'specialty'      => 'Cardiology',
            'start_time'     => '09:00:00',
            'end_time'       => '17:00:00',
            'is_strict_time' => true,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
        [
            'name'           => 'Dr. Jane Doe',
            'specialty'      => 'Dermatology',
            'start_time'     => '10:00:00',
            'end_time'       => '16:00:00',
            'is_strict_time' => false,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
        [
            'name'           => 'Dr. Michael Brown',
            'specialty'      => 'Neurology',
            'start_time'     => '08:30:00',
            'end_time'       => '15:30:00',
            'is_strict_time' => true,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
        [
            'name'           => 'Dr. Emily White',
            'specialty'      => 'Pediatrics',
            'start_time'     => '09:00:00',
            'end_time'       => '17:30:00',
            'is_strict_time' => false,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
        [
            'name'           => 'Dr. William Green',
            'specialty'      => 'Orthopedics',
            'start_time'     => '10:00:00',
            'end_time'       => '18:00:00',
            'is_strict_time' => true,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
        [
            'name'           => 'Dr. Olivia Taylor',
            'specialty'      => 'Ophthalmology',
            'start_time'     => '09:30:00',
            'end_time'       => '17:00:00',
            'is_strict_time' => false,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
        [
            'name'           => 'Dr. James Wilson',
            'specialty'      => 'Psychiatry',
            'start_time'     => '08:00:00',
            'end_time'       => '14:00:00',
            'is_strict_time' => true,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
        [
            'name'           => 'Dr. Sophia Martinez',
            'specialty'      => 'Gynecology',
            'start_time'     => '09:00:00',
            'end_time'       => '15:30:00',
            'is_strict_time' => false,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
        [
            'name'           => 'Dr. Daniel Lee',
            'specialty'      => 'ENT',
            'start_time'     => '10:00:00',
            'end_time'       => '17:00:00',
            'is_strict_time' => true,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
        [
            'name'           => 'Dr. Isabella Harris',
            'specialty'      => 'Gastroenterology',
            'start_time'     => '08:30:00',
            'end_time'       => '16:30:00',
            'is_strict_time' => false,
            'created_at'     => $now,
            'updated_at'     => $now,
        ],
    ]);


    }
}
