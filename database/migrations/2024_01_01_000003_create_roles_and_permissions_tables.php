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
        // Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->json('permissions')->nullable(); // Cached permissions for performance
            $table->boolean('is_default')->default(false);
            $table->boolean('is_system')->default(false); // System roles cannot be deleted
            $table->timestamps();
            
            $table->unique(['tenant_id', 'name', 'guard_name']);
            $table->index(['tenant_id', 'guard_name']);
        });

        // Permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->string('group')->nullable(); // Group permissions by module
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['name', 'guard_name']);
            $table->index(['guard_name', 'group']);
        });

        // Role has permissions pivot table
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            
            $table->primary(['permission_id', 'role_id']);
        });

        // Model has permissions table (direct user permissions)
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            
            $table->index(['model_id', 'model_type']);
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        // Model has roles table (user roles)
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            
            $table->index(['model_id', 'model_type']);
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // Feature flags table for tenant-specific features
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('feature_name');
            $table->boolean('is_enabled')->default(true);
            $table->json('configuration')->nullable(); // Feature-specific config
            $table->timestamp('enabled_at')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->timestamps();
            
            $table->unique(['tenant_id', 'feature_name']);
            $table->index(['tenant_id', 'is_enabled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
