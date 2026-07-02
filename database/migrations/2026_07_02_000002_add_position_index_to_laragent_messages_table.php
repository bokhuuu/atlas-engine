<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laragent_messages', function (Blueprint $table) {
            $table->index(
                ['session_key', 'position'],
                'laragent_messages_session_key_position_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('laragent_messages', function (Blueprint $table) {
            $table->dropIndex('laragent_messages_session_key_position_index');
        });
    }
};
