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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('from_login_sid')->index();
            $table->enum('transaction_type', ['deposit', 'withdrawal'])->index();
            $table->enum('status', ['approved'])->index(); // Only storing approved
            $table->decimal('processed_amount', 15, 2);
            $table->date('transaction_date')->index();
            $table->timestamp('created_at_api'); // Original createdAt from API
            $table->timestamp('processed_at_api')->nullable(); // Original processedAt from API
            $table->json('metadata')->nullable(); // Store additional data if needed
            $table->timestamps();

            // Composite indexes for queries with shortened names
            $table->index(['transaction_type', 'status', 'transaction_date'], 'tx_details_type_status_date_idx');
            $table->index(['from_login_sid', 'transaction_type'], 'tx_details_user_type_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
