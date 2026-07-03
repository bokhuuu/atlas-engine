<?php

declare(strict_types=1);

namespace App\Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\HasNeighbors;
use Pgvector\Laravel\Vector;

class Document extends Model
{
    use HasNeighbors;

    protected $fillable = [
        'content',
        'embedding',
        'source_type',
        'source_id',
    ];

    protected $casts = ['embedding' => Vector::class];
}
