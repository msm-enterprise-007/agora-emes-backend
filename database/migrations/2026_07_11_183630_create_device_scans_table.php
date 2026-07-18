<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_scans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('mac_address', 17);

            $table->string('ip_address', 45);

            $table->enum('scan_source', [
                'mikrotik',
                'olax',
                'arp',
                'snmp',
                'manual'
            ]);

            $table->boolean('is_online')->default(true);

            $table->timestamp('scanned_at');

            $table->timestamps();

            $table->index(['device_id', 'scanned_at']);
            $table->index('mac_address');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_scans');
    }
};