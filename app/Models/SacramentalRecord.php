<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SacramentalRecord extends Model
{
    protected $fillable = [
        'user_id',
        'sacrament_type',
        'date_received',
        'location',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'date_received' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
