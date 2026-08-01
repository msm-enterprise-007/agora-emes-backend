<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->unsignedInteger('late_minutes')
                ->default(0)
                ->after('break_minutes');
    
            $table->enum('arrival_status', [
                'on_time',
                'late',
            ])
            ->default('on_time')
            ->after('late_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'late_minutes',
                'arrival_status',
            ]);
        });
    }
};
