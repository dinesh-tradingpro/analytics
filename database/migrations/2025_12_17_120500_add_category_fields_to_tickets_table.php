<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->nullable()->after('status')->index();
            $table->string('category_title')->nullable()->after('category_id')->index();
            // manager stays for now but won't be used
            $table->index(['category_id', 'ticket_date'], 'tickets_category_date_idx');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('tickets_category_date_idx');
            $table->dropColumn(['category_id', 'category_title']);
        });
    }
};
