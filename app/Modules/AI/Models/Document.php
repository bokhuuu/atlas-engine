<?php

declare(strict_types=1);

namespace App\Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['content', 'embedding'];

    protected $casts = ['embedding' => 'array'];
}
