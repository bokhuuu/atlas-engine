<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tracks background Excel export jobs requested from Data Hub.
 *
 * Created when user clicks Download Excel - job generates file in background.
 * Frontend polls status until completed, then downloads via file_path.
 * Records expire after 24 hours - cleaned up by pruning command.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('export_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('pending');
            $table->string('source');
            $table->json('filters');
            $table->string('file_path')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_jobs');
    }
};
