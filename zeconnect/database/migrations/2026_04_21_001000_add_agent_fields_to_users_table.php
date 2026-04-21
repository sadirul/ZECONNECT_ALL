<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->default(DB::raw('(UUID())'))->unique()->after('id');
            $table->string('mobile', 20)->nullable()->after('name');
            $table->text('address')->nullable()->after('password');
            $table->string('profile_pic')->nullable()->after('address');
            $table->enum('role', ['agent', 'user'])->default('user')->after('profile_pic');
            $table->boolean('is_active')->default(true)->after('role');
        });

        DB::statement("UPDATE users SET uuid = UUID() WHERE uuid IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'mobile', 'address', 'profile_pic', 'role', 'is_active']);
        });
    }
};
