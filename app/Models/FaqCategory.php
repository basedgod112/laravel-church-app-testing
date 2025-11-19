<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;

/**
 * @method static firstOrCreate(string[] $array)
 */
class FaqCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function faqs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Faq::class);
    }
}
