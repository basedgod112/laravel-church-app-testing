<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @method static findOrFail($id)
 * @method static where(string $string, $type)
 * @property mixed $title
 * @property mixed $content
 * @property mixed|null $author
 * @property CarbonInterface|Carbon|mixed $published_at
 * @property mixed $type
 * @property mixed|string $image
 */
class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'content',
        'published_at',
        'author',
        'type',
    ];
}
