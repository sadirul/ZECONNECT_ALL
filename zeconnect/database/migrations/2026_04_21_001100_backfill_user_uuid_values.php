<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("UPDATE users SET uuid = UUID() WHERE uuid IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: data backfill only.
    }
};
