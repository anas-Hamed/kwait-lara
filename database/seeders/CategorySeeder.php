<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $image = Image::make(database_path('factories/faker.PNG'));
        $filename = 'uploads/category/category-base.png';
        if (!Storage::exists($filename)) {
            Storage::disk('public')->put($filename, $image->stream('png', 80));
        }
        $categories = [
            [
                'name' => 'جهات حكومية',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'وزارات',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'هيئات',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مراكز خدمة المواطن',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مؤسسات',
                        'image' => $filename,
                    ]
                ],
            ],
            [
                'name' => 'شركات خاصة',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'استثمار',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'بنوك',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'تامين',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'شركات التموين الغذائي',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'الاستيراد والتصديري',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'شركات الاثاث',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'التصميم و الديكور',
                        'image' => $filename,
                    ],
                ],
            ],
            [
                'name' => 'الكترونيات',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'شركات الالكترونيات الكبرى',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'محلات الهواتف',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'محلات الاكسسوارات',
                        'image' => $filename,
                    ]
                ],
            ],
            [
                'name' => 'رياضة',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'نوادي صحية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'نوادي صحية نسائية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'شركات اجهزة رياضية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'محلات المكملات الغذائية',
                        'image' => $filename,
                    ],
                ],
            ],
            [
                'name' => 'الدعاية',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'قنوات',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'قنوات اخبارية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'صحف',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مجلات',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'شركات الدعاية والاعلان',
                        'image' => $filename,
                    ],
                ],
            ],
            [
                'name' => 'تعليم',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'مدارس خاصة',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مدارس حكومية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مدارس أجنبية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'معاهد تقوية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مدارس ثنائية اللغة',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'معاهد دينية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'جامعات حكومية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'جامعات خاصة',
                        'image' => $filename,
                    ],
                ],
            ],
            [
                'name' => 'الصحة والجمال',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'مستشفيات خاصة',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مستشفيات حكومية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'عيادات تجميل',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مراكز و معاهد صحية',
                        'image' => $filename,
                    ],
                ],
            ],
            [
                'name' => 'الغذاء',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'المطاعم',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'كافيهات',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'محلات العصائر',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'المطابخ المركزية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'سوبرماركت',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مراكز بيع الجملة',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'بقالات',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'شركات الخضار و الفاكهة',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'جزارين',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'عطار او محلات البهارات',
                        'image' => $filename,
                    ],
                ],
            ],
            [
                'name' => 'خدمات',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'شركات توصيل',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'تكسي',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'صيانة',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'نقل',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'برمجة',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'صحي',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'نجار',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'حداد',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'كهربائي',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'تصميم',
                        'image' => $filename,
                    ],
                ],
            ],
            [
                'name' => 'سيارات',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'وكالات السيارات',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'شركات تاجير',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'معارض سيارات',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'شركات التامين',
                        'image' => $filename,
                    ],
                ],
            ],
            [
                'name' => 'تجميل و مساج',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'صالونات نسائية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'صالونات رجالية',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'محلات مكياج',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'معاهد المساج',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'حمامات عربية',
                        'image' => $filename,
                    ],
                ],
            ],
            [
                'name' => 'مشاريع منزلية',
                'image' => $filename,
                'children' => [
                    [
                        'name' => 'مشاريع حلا',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مشاريع ملابس وعبايات',
                        'image' => $filename,
                    ],
                    [
                        'name' => 'مشاريع هدايا واكسسوارات',
                        'image' => $filename,
                    ]
                ],
            ],
        ];
        foreach ($categories as $category){

            $current = Category::query()->create(['name' => ['ar' => $category['name']],'image' => $category['image'],'parent_id' => null]);
            foreach ($category['children'] as $child) {
                Category::query()->create(['name' => ['ar' => $child['name']],'image' => $child['image'],'parent_id' => $current->id]);
            }
        }
    }
}
