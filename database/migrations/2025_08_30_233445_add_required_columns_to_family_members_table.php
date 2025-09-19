<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreignId('family_member_id')->after('id')->constrained('family_members')->onDelete('cascade');
            $table->string('name')->after('family_member_id');
            $table->enum('type', ['main', 'house', 'apartment', 'isolated'])->default('main')->after('name');
            $table->decimal('usd_balance', 15, 2)->default(0)->after('type');
            $table->decimal('egp_balance', 15, 2)->default(0)->after('usd_balance');
            $table->boolean('is_isolated')->default(false)->after('egp_balance');
            $table->date('isolation_until')->nullable()->after('is_isolated');
            $table->text('description')->nullable()->after('isolation_until');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['family_member_id']);
            $table->dropColumn([
                'family_member_id', 'name', 'type', 'usd_balance', 
                'egp_balance', 'is_isolated', 'isolation_until', 'description'
            ]);
        });
    }
};