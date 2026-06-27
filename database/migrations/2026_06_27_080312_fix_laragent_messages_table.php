<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the laragent_messages table with the exact schema
 * required by LarAgent's EloquentStorage driver.
 *
 * Column names and types match LaragentMessage model's $fillable
 * and $casts arrays exactly — any mismatch causes silent save failures.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('laragent_messages');

        Schema::create('laragent_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_key')->index();
            $table->unsignedInteger('position')->default(0);
            $table->string('role');
            $table->json('content')->nullable();
            $table->string('message_uuid')->nullable();
            $table->string('message_created')->nullable();
            $table->json('tool_calls')->nullable();
            $table->string('tool_call_id')->nullable();
            $table->json('usage')->nullable();
            $table->json('metadata')->nullable();
            $table->json('extras')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laragent_messages');
    }
};
