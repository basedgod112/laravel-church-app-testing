<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @method static orderBy(string $string, string $string1)
 * @method static create(array $array)
 * @property mixed $email
 * @property mixed $name
 * @property mixed $message
 * @property mixed $reply_message
 * @property CarbonInterface|Carbon|mixed $replied_at
 */
class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'message',
        'reply_message',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];
}

