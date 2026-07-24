<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds has_bill_of_sale as a first-class boolean column.
     *
     * Previously stored in extras JSON - promoted to a proper column
     * because купчая (bill of sale) significantly affects Baku land
     * pricing and must be filterable, sortable, and visible in exports.
     * Nullable - only populated for bina.az listings, null for list.am.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->boolean('has_bill_of_sale')->nullable()->after('is_new_building');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('has_bill_of_sale');
        });
    }
};
