<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add price change tracking fields to listings.
     *
     * previous_price  — the price before the most recent change
     * price_change_pct — percentage change (negative = reduced, positive = increased)
     * price_changed_at — when the price change was detected
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->decimal('previous_price', 12, 2)->nullable()->after('price');
            $table->decimal('price_change_pct', 8, 2)->nullable()->after('previous_price');
            $table->timestamp('price_changed_at')->nullable()->after('price_change_pct');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['previous_price', 'price_change_pct', 'price_changed_at']);
        });
    }
};
