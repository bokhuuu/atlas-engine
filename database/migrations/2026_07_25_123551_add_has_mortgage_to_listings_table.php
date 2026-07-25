<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds has_mortgage as a first-class boolean column.
     *
     * Mortgage availability affects buyer financing options for Baku
     * land listings. Stored as proper column for filtering and export.
     * Nullable — only populated for bina.az listings, null for list.am.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->boolean('has_mortgage')->nullable()->after('has_bill_of_sale');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('has_mortgage');
        });
    }
};
