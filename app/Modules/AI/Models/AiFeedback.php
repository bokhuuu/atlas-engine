<?php

declare(strict_types=1);

namespace App\Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;

class AiFeedback extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'response_text',
        'vote',
        'comment',
    ];

    protected $casts = [
        'vote' => 'integer',
    ];
}
