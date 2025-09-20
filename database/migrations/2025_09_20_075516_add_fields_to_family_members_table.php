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
        Schema::table('family_members', function (Blueprint $table) {
             $table->unsignedBigInteger('role_id')->nullable()->after('position_id');
            $table->string('phone', 30)->nullable()->after('email');
            $table->date('dob')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('dob');
            $table->string('personal_id', 50)->nullable()->after('gender');
            $table->string('photo')->nullable()->after('personal_id');
            $table->string('address', 255)->nullable()->after('photo');
            $table->string('emergency_name', 100)->nullable()->after('address');
            $table->string('emergency_phone', 30)->nullable()->after('emergency_name');
            $table->text('notes')->nullable()->after('emergency_phone');
            // Add foreign key for role if you want:
            // $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
             $table->dropColumn([
                'role_id', 'phone', 'dob', 'gender', 'personal_id', 'photo',
                'address', 'emergency_name', 'emergency_phone', 'notes'
            ]);
        });
    }
};
