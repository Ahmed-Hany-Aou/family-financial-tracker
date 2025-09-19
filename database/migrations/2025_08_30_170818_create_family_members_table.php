<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Single Responsibility: This migration only adds columns to family_members table
 * Following SOLID principles for database design
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->string('name', 100)->after('id');
            $table->string('email')->unique()->after('name');
            $table->enum('role', ['father', 'son'])->default('son')->after('email');
            $table->enum('permission_level', ['read_only', 'read_write'])->default('read_only')->after('role');
            $table->timestamp('email_verified_at')->nullable()->after('permission_level');
            $table->string('password')->after('email_verified_at');
            $table->boolean('is_active')->default(true)->after('password');
            $table->rememberToken()->after('is_active');
            
            // Indexes for performance (Database design best practices)
            $table->index(['role', 'is_active'], 'idx_role_active');
            $table->index('email', 'idx_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropIndex('idx_role_active');
            $table->dropIndex('idx_email');
            $table->dropColumn([
                'name', 'email', 'role', 'permission_level', 
                'email_verified_at', 'password', 'is_active', 'remember_token'
            ]);
        });
    }
};