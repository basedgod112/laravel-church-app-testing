<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Resource::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'image' => 'default-resources-image.jpg',
            'content' => $this->faker->paragraphs(3, true),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'author' => $this->faker->name(),
            'resource_category_id' => ResourceCategory::query()->inRandomOrder()->value('id'),
        ];
    }
}
