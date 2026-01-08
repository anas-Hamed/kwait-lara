<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $top_level_categories = Category::where('parent_id', null)->pluck('id')->toArray();
        return [
            'name' => $this->faker->name(),
            'parent_id' =>  $this->faker->randomElement($top_level_categories),
            'order' => rand(0,10),
            'is_active' =>$this->faker->randomElement([1, 0])
        ];
    }
}
