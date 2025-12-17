<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Drop the composite index if it exists (ignore errors if already dropped)
            try {
                $table->dropIndex('tickets_category_date_idx');
            } catch (\Throwable $e) {
                // no-op
            }
        });

        if (Schema::hasColumn('tickets', 'category_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('category_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (! Schema::hasColumn('tickets', 'category_id')) {
                $table->unsignedInteger('category_id')->nullable()->after('status')->index();
            }
            // Recreate the index if both columns exist
            if (Schema::hasColumn('tickets', 'category_id') && Schema::hasColumn('tickets', 'ticket_date')) {
                try {
                    $table->index(['category_id', 'ticket_date'], 'tickets_category_date_idx');
                } catch (\Throwable $e) {
                    // no-op
                }
            }
        });
    }
};
