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
        Schema::create('user_analytics_cache', function (Blueprint $table) {
            $table->id();
            $table->string('metric_type'); // 'new_users', 'active_users', 'inactive_users'
            $table->json('chart_data'); // Store the processed chart data
            $table->integer('total_count'); // Total count for this metric
            $table->json('metadata')->nullable(); // Additional metadata like date ranges, breakdowns
            $table->integer('total_records_fetched'); // Number of records fetched from API
            $table->timestamp('data_date'); // Date this data represents
            $table->timestamp('synced_at'); // When this data was last synced
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['metric_type', 'data_date']);
            $table->index('synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_analytics_cache');
    }
};
