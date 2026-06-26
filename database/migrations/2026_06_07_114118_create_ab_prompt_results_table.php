<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores per-session A/B test variant assignments and feedback votes.
 * Each session is assigned once to variant A or B and stays there.
 * vote is nullable — filled when the user submits feedback after the AI response.
 * Results are read by AbTestService::getResults() to compare variant performance.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ab_prompt_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ab_prompt_test_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->index();
            $table->char('variant', 1);
            $table->string('prompt_key');
            $table->tinyInteger('vote')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ab_prompt_results');
    }
};
