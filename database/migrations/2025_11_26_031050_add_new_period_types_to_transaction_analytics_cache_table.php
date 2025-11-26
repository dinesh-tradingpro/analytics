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
        // Add new enum values to period_type
        DB::statement("ALTER TABLE transaction_analytics_cache MODIFY COLUMN period_type ENUM('all_time', 'yearly', 'monthly', 'weekly', 'daily', 'current_month', 'last_7_days')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove new enum values from period_type
        DB::statement("ALTER TABLE transaction_analytics_cache MODIFY COLUMN period_type ENUM('all_time', 'yearly', 'monthly', 'weekly', 'daily')");
    }
};
