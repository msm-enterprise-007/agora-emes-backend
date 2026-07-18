<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('title');

            $table->date('start_date');

            $table->date('end_date');

            $table->time('work_start_time');

            $table->time('break_start_time')->nullable();

            $table->time('break_end_time')->nullable();

            $table->time('work_end_time');

            $table->unsignedInteger('authorized_absence_minutes')->default(120);

            $table->enum('status', [
                'pending',
                'active',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->text('description')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};