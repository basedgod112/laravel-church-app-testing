<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $resource_id
 * @property mixed $user_id
 * @method static firstOrCreate(array $array)
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id',
        'resource_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}

