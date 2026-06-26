<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores versioned system prompts for dynamic prompt management.
 * Only one version per key can be active at a time — enforced by PromptService.
 * Update the active prompt via PromptService::create() without redeploying.
 * Roll back to a previous version via PromptService::rollback().
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompt_versions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index();
            $table->longText('content');
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_active')->default(false);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_versions');
    }
};
