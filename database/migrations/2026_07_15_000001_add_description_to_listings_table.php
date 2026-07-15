<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a description column to the listings table.
 *
 * Stores the raw listing description text as scraped from the source site -
 * Armenian, Russian, Azerbaijani, or Georgian depending on the market.
 * Populated when the scraper extracts description text from detail pages.
 *
 * translated_description (already exists) stores the English translation
 * produced by TranslationService after this column is populated.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->text('description')->nullable()->after('extras');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
