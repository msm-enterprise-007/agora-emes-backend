<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('internship_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->timestamp('check_in_at')->nullable();

            $table->timestamp('break_out_at')->nullable();

            $table->timestamp('break_in_at')->nullable();

            $table->timestamp('check_out_at')->nullable();

            $table->unsignedInteger('worked_minutes')->default(0);

            $table->unsignedInteger('break_minutes')->default(0);

            $table->enum('status', [
                'present',
                'late',
                'on_break',
                'unauthorized_leave',
                'absent',
                'completed'
            ])->default('present');

            $table->boolean('is_verified')->default(false);

            $table->timestamps();

            $table->unique(['internship_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};