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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('type', ['admin', 'staff', 'driver', 'passenger'])->default('passenger');
            $table->enum('status', ['active', 'inactive', 'suspended', 'banned'])->default('active');
            $table->json('preferences')->nullable();
            $table->json('meta')->nullable(); // Additional metadata
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->json('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            $table->unique(['tenant_id', 'email']);
            $table->index(['tenant_id', 'type', 'status']);
            $table->index(['tenant_id', 'email']);
            $table->index(['tenant_id', 'phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
