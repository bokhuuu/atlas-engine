<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Creates a compressed PostgreSQL dump and uploads it to Backblaze B2.
 *
 * Runs daily via the scheduler (routes/console.php).
 * Keeps the last 7 days of backups - older files are deleted from B2 automatically.
 *
 * Requires rclone configured with a 'b2' remote and these env vars:
 *   B2_KEY_ID, B2_APPLICATION_KEY, B2_BUCKET
 *
 * Usage: php artisan db:backup
 */
class BackupDatabase extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Dump PostgreSQL database and upload to Backblaze B2';

    public function handle(): int
    {
        $database = config('database.connections.pgsql.database');
        $username = config('database.connections.pgsql.username');
        $bucket = env('B2_BUCKET');

        if (! $bucket) {
            $this->error('B2_BUCKET is not set in .env');

            return self::FAILURE;
        }

        $filename = 'atlas_'.now()->format('Y_m_d_His').'.sql.gz';
        $localPath = storage_path("backups/{$filename}");

        if (! is_dir(storage_path('backups'))) {
            mkdir(storage_path('backups'), 0755, true);
        }

        $this->info("Creating database dump: {$filename}");

        $dumpCommand = sprintf(
            'docker compose -f /var/www/atlas/docker-compose.prod.yml exec -T postgres pg_dump -U %s %s | gzip > %s',
            escapeshellarg($username),
            escapeshellarg($database),
            escapeshellarg($localPath)
        );

        exec($dumpCommand, $output, $exitCode);

        if ($exitCode !== 0 || ! file_exists($localPath)) {
            $this->error('pg_dump failed.');
            Log::error('db:backup - pg_dump failed', ['exit_code' => $exitCode]);

            return self::FAILURE;
        }

        $sizeMb = round(filesize($localPath) / 1024 / 1024, 2);
        $this->info("Dump created: {$sizeMb} MB");

        $this->info("Uploading to B2 bucket: {$bucket}");

        $uploadCommand = sprintf(
            'rclone copy %s b2:%s/',
            escapeshellarg($localPath),
            escapeshellarg($bucket)
        );

        exec($uploadCommand, $output, $exitCode);

        if ($exitCode !== 0) {
            $this->error('rclone upload failed.');
            Log::error('db:backup - rclone upload failed', ['exit_code' => $exitCode]);

            return self::FAILURE;
        }

        $this->info('Upload complete.');

        unlink($localPath);

        $deleteCommand = sprintf(
            'rclone delete b2:%s --min-age 7d',
            escapeshellarg($bucket)
        );

        exec($deleteCommand, $output, $exitCode);

        if ($exitCode !== 0) {
            Log::warning('db:backup - old backup cleanup failed', ['exit_code' => $exitCode]);
        }

        $this->info('Old backups cleaned up.');

        Log::info('db:backup - completed successfully', [
            'filename' => $filename,
            'size_mb' => $sizeMb,
        ]);

        return self::SUCCESS;
    }
}
