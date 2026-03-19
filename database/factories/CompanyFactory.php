<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories_ids = Category::query()->where('parent_id','!=',null)->pluck('id')->toArray();
        $image = Image::make(database_path('factories/faker.PNG'));
        $filename = 'uploads/category/category-base.png';
        if (!Storage::exists($filename)) {
            Storage::disk('public')->put($filename, $image->stream('png', 80));
        }
        $name = $this->faker->name;
        return [
            'ar_name' => $name,
            'slug' => Str::slug($name) ,
            'en_name' => $this->faker->name,
            'email' => $this->faker->companyEmail,
            'category_id' => $categories_ids[array_rand($categories_ids)],
            'user_id' => User::factory()->create()->id,
            'phone' => $this->faker->phoneNumber,
            'whatsapp' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
            'instagram' => $this->faker->url,
            'twitter' => $this->faker->url,
            'facebook' => $this->faker->url,
            'snapchat' => $this->faker->url,
            'linkedin' => $this->faker->url,
            'about' => $this->faker->realText(100),
            'tags' => [$this->faker->realText(10),$this->faker->realText(12),$this->faker->realText(11)],
            'location' =>[
                /*
                 * kuwait city center
                 * Latitude: 29.3697200°
                 * Longitude: 47.9783300°
                 */
                'lat' => $this->faker->latitude(28.30, 30.5),
                'lng' => $this->faker->longitude(46.33, 48.30),
            ],
            'is_active' => true,
            'has_paid' => true,
            'average_rate' => $this->faker->numberBetween(1,5),
            'image' => Image::make('https://source.unsplash.com/400x400/?company,building')

        ];
    }
}
