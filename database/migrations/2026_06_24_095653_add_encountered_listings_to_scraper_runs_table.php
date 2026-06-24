<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scraper_runs', function (Blueprint $table) {
            $table->unsignedInteger('encountered_listings')->default(0)->after('saved_listings');
        });
    }

    public function down(): void
    {
        Schema::table('scraper_runs', function (Blueprint $table) {
            $table->dropColumn('encountered_listings');
        });
    }
};
