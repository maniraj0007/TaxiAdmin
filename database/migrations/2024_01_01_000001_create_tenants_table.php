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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('subdomain')->unique()->nullable();
            $table->string('database')->nullable();
            $table->json('settings')->nullable();
            $table->json('features')->nullable(); // Feature flags for this tenant
            $table->string('status')->default('active'); // active, suspended, inactive
            $table->string('plan')->default('basic'); // basic, standard, premium, enterprise
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->timestamps();
            
            $table->index(['domain', 'status']);
            $table->index(['subdomain', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
