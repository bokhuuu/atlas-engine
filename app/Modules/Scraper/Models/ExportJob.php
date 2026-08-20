<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tracks a background Excel export job requested from Data Hub.
 *
 * Lifecycle: pending → processing → completed | failed
 * Frontend polls status endpoint until completed, then downloads via file_path.
 */
class ExportJob extends Model
{
    protected $fillable = [
        'status',
        'source',
        'filters',
        'file_path',
        'error',
        'expires_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'expires_at' => 'datetime',
    ];
}
