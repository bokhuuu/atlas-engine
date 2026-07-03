<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('embedding');
            $table->string('source_type')->nullable()->after('content');
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
        });

        DB::statement('ALTER TABLE documents ADD COLUMN embedding vector(1536)');
        DB::statement('CREATE INDEX documents_embedding_hnsw ON documents USING hnsw (embedding vector_cosine_ops)');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS documents_embedding_hnsw');

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['embedding', 'source_type', 'source_id']);
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->json('embedding')->nullable();
        });
    }
};
