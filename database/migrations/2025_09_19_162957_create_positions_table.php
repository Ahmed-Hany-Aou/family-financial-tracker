<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // eg. father, mother, son, daughter, other
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Update family_members table
        Schema::table('family_members', function (Blueprint $table) {
            $table->unsignedBigInteger('position_id')->nullable()->after('family_id');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
            $table->dropColumn('position'); // Safest if you already migrated before! Otherwise just remove the enum in the migration.
        });
    }

    public function down()
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
            $table->enum('position', ['father', 'mother', 'son', 'daughter', 'other'])->default('other'); // only add back if rolling back!
        });
        Schema::dropIfExists('positions');
    }
};
