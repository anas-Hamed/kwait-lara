<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\QaItem;
use Illuminate\Database\Seeder;

class QaItemSeeder extends Seeder
{
    public function run()
    {
        $parentCategories = Category::query()->whereNull('parent_id')->get();

        foreach ($parentCategories as $category) {
            QaItem::query()->updateOrCreate(
                [
                    'category_id' => $category->id,
                    'question' => 'ما هي خدمات قسم ' . $category->getTranslation('name', 'ar') . '؟',
                ],
                [
                    'answer' => 'نقدم مجموعة متنوعة من الخدمات في قسم ' . $category->getTranslation('name', 'ar') . ' تشمل جميع الاحتياجات الأساسية والمتقدمة للعملاء.',
                ]
            );

            QaItem::query()->updateOrCreate(
                [
                    'category_id' => $category->id,
                    'question' => 'كيف يمكنني التسجيل في قسم ' . $category->getTranslation('name', 'ar') . '؟',
                ],
                [
                    'answer' => 'يمكنك التسجيل من خلال التطبيق أو الموقع الإلكتروني واختيار القسم المناسب لك.',
                ]
            );
        }
    }
}
