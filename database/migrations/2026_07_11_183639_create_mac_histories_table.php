<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mac_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('device_scan_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('old_mac_address', 17);

            $table->string('new_mac_address', 17);

            $table->enum('change_type', [
                'mac_changed',
                'mac_restored',
                'suspected_spoofing'
            ]);

            $table->text('description')->nullable();

            $table->timestamp('detected_at');

            $table->timestamps();

            $table->index('detected_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mac_histories');
    }
};