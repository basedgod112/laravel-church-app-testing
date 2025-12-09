<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static Model|static firstOrCreate(array $attributes, array $values = [])
 * @method static Model|static create(array $data)
 * @method static Model|static findOrFail($id)
 * @method static Builder|static orderBy(string $column, string $direction = 'asc')
 * @method static Builder|static whereHas(string $relation, Closure $callback)
 * @method static Builder|static where(string $column, $operator = null, $value = null, string $boolean = 'and')
 * @method static pluck(string $string)
 */
class FaqCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }
}
