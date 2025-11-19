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
        Schema::create('transaction_analytics_cache', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ['deposit', 'withdrawal'])->index();
            $table->enum('status', ['approved', 'declined'])->index();
            $table->enum('period_type', ['daily', 'weekly', 'monthly', 'yearly', 'all_time'])->index();
            $table->json('chart_data'); // Contains aggregated data for charts
            $table->integer('total_count')->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->json('top_transactions'); // Top 10 transactions
            $table->json('metadata')->nullable(); // Additional metadata
            $table->integer('total_records_fetched')->default(0);
            $table->date('period_start')->nullable(); // For time-based periods
            $table->date('period_end')->nullable();   // For time-based periods
            $table->timestamp('synced_at');
            $table->timestamps();

            // Composite indexes for efficient querying
            $table->index(['transaction_type', 'status', 'period_type'], 'tx_analytics_composite_idx');
            $table->index('synced_at', 'tx_analytics_synced_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_analytics_cache');
    }
};
