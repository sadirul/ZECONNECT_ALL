<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('is_active');
            $table->string('otp', 6)->nullable()->after('is_verified');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
            $table->string('reset_otp', 6)->nullable()->after('otp_expires_at');
            $table->timestamp('reset_otp_expires_at')->nullable()->after('reset_otp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_verified',
                'otp',
                'otp_expires_at',
                'reset_otp',
                'reset_otp_expires_at',
            ]);
        });
    }
};
