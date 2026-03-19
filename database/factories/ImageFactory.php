<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\ImageItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ImageItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $image = \Intervention\Image\Facades\Image::make(database_path('factories/faker.PNG'));
        $filename = 'upload/category/category-base.png';
        $company = Company::query()->inRandomOrder()->first() ?? Company::factory()->create();

        if (!Storage::exists($filename)){
            Storage::disk('public')->put($filename,$image->stream('png',80));
        }
        return [
            'path' => $filename,
            'related_id' => $company->id,
            'related_type' => Company::class
        ];

    }
}
