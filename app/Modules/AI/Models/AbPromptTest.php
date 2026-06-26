<?php

declare(strict_types=1);

namespace App\Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AbPromptTest extends Model
{
    protected $fillable = [
        'name',
        'prompt_key_a',
        'prompt_key_b',
        'traffic_split',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'traffic_split' => 'integer',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(AbPromptResult::class);
    }
}
