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
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();
        
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
        
            $table->enum('application_type', [
                'internship',
                'formation',
            ]);
        
            $table->string('education_level');
        
            $table->text('motivation')->nullable();
        
            // Appareils
            $table->string('phone_mac_address');
            $table->string('laptop_mac_address')->nullable();
        
            // Validation
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');
        
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        
            $table->timestamp('reviewed_at')->nullable();
        
            $table->text('admin_comment')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};
