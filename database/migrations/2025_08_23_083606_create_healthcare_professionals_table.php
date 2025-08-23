<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('healthcare_professionals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialty');
            $table->boolean('is_strict_time')->default(false);
            $table->time('start_time')->nullable(); // doctor's working start time
            $table->time('end_time')->nullable();   // doctor's working end time
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('healthcare_professionals');
    }
};
