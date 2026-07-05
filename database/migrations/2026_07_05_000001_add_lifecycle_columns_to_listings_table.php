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
            $table->timestamp('last_seen_at')->nullable()->after('scraped_at');
            $table->timestamp('last_checked_at')->nullable()->after('last_seen_at');
            $table->timestamp('disappeared_at')->nullable()->after('last_checked_at');
            $table->string('screenshot_url')->nullable()->after('disappeared_at');
            $table->text('translated_description')->nullable()->after('screenshot_url');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'last_seen_at',
                'last_checked_at',
                'disappeared_at',
                'screenshot_url',
                'translated_description',
            ]);
        });
    }
};
