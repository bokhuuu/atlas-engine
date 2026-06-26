<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores LarAgent session identity records.
 * Maps session keys to agent identities for persistent conversation tracking.
 * Created automatically by LarAgent when a new session is started.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laragent_session_identities', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('agent_class');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laragent_session_identities');
    }
};
