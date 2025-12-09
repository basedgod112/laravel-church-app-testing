<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static findOrFail($id)
 * @method static create(array $data)
 * @method static firstOrCreate(string[] $array, array $array1)
 */
class Faq extends Model
{
    use HasFactory;

    protected $fillable = ['faq_category_id', 'question', 'answer'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
}

