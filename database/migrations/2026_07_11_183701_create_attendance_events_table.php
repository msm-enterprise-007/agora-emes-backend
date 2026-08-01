<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attendance_session_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('device_scan_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->enum('event_type', [
                'check_in',
                'break_out',
                'break_in',
                'check_out',
                'device_detected',
                'device_lost',
                'verification_requested',
                'verification_confirmed',
                'verification_failed',
                'mac_changed'
            ]);

            $table->enum('source', [
                'mikrotik',
                'olax',
                'arp',
                'snmp',
                'manual',
                'system'
            ]);

            $table->timestamp('event_time');

            $table->json('metadata')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(['attendance_session_id', 'event_time']);
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_events');
    }
};