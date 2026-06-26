<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores A/B prompt test configurations.
 * Each test compares two prompt variants (A and B) by measuring positive vote rate.
 * traffic_split defines percentage of sessions assigned to variant A (0-100).
 * Only one test should be active at a time — enforced at application level.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ab_prompt_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('prompt_key_a');
            $table->string('prompt_key_b');
            $table->unsignedTinyInteger('traffic_split')->default(50);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ab_prompt_tests');
    }
};
