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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique();
            $table->string('status')->index();
            $table->string('manager')->nullable()->index();
            $table->date('ticket_date')->nullable()->index();
            $table->timestamp('created_at_api')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['status', 'ticket_date'], 'tickets_status_date_idx');
            $table->index(['manager', 'status'], 'tickets_manager_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
