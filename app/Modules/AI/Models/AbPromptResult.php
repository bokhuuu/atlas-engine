<?php

declare(strict_types=1);

namespace App\Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbPromptResult extends Model
{
    protected $fillable = [
        'ab_prompt_test_id',
        'session_id',
        'variant',
        'prompt_key',
        'vote',
    ];

    protected $casts = [
        'vote' => 'integer',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(AbPromptTest::class, 'ab_prompt_test_id');
    }
}
