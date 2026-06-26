<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores per-session conversation history for LarAgent.
 * Every message (user and assistant) is persisted here automatically
 * by LarAgent when $history = 'database' is set on the agent.
 * Keyed by session_id so each user gets their own isolated history.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laragent_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->string('role');
            $table->longText('content');
            $table->json('tool_calls')->nullable();
            $table->json('tool_results')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laragent_messages');
    }
};
