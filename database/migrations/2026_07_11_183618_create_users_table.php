<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('matricule', 30)->unique();

            $table->string('first_name');
            $table->string('last_name');

            $table->string('email')->unique();

            $table->string('phone', 20)->nullable();

            $table->string('password');

            $table->boolean('is_active')->default(true);

            $table->rememberToken();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};