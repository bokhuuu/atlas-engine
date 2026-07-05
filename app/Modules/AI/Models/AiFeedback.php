<?php

declare(strict_types=1);

namespace App\Modules\AI\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiFeedback extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'message_id',
        'response_text',
        'vote',
        'comment',
    ];

    protected $casts = [
        'vote' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
