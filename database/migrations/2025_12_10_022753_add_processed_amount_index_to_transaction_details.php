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
        Schema::table('transaction_details', function (Blueprint $table) {
            // Composite index for queries that filter by type, status, and order by amount
            // This speeds up getTopTransactions queries significantly
            $table->index(['transaction_type', 'status', 'processed_amount'], 'tx_details_type_status_amount_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropIndex('tx_details_type_status_amount_idx');
        });
    }
};
