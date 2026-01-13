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
            // Keep from_login_sid (wallet id) but add explicit from_user_id
            $table->unsignedBigInteger('from_user_id')->nullable()->after('from_login_sid')->index();

            // Preserve original amounts/currencies and store USD-converted fields
            $table->decimal('requested_amount', 15, 2)->nullable()->after('status');
            $table->string('requested_currency', 8)->nullable()->after('requested_amount');
            $table->string('processed_currency', 8)->nullable()->after('requested_currency');
            $table->decimal('processed_amount_usd', 15, 2)->nullable()->after('processed_amount');
            $table->decimal('requested_amount_usd', 15, 2)->nullable()->after('processed_amount_usd');

            // Indexes to help queries by user/wallet
            $table->index(['from_user_id', 'transaction_type'], 'tx_details_userid_type_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropIndex('tx_details_userid_type_idx');
            $table->dropColumn([
                'from_user_id',
                'requested_amount',
                'requested_currency',
                'processed_currency',
                'processed_amount_usd',
                'requested_amount_usd',
            ]);
        });
    }
};
