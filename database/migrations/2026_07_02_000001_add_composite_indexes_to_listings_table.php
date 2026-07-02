<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->index(
                ['source_profile_name', 'district', 'price_per_sqm'],
                'listings_source_district_price_per_sqm_index'
            );

            $table->index(
                ['source_profile_name', 'price_changed_at', 'price_change_pct'],
                'listings_source_price_changed_at_pct_index'
            );

            $table->index(
                ['source_profile_name', 'price', 'district', 'rooms'],
                'listings_source_price_district_rooms_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropIndex('listings_source_district_price_per_sqm_index');
            $table->dropIndex('listings_source_price_changed_at_pct_index');
            $table->dropIndex('listings_source_price_district_rooms_index');
        });
    }
};
