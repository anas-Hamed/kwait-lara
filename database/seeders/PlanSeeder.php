<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'مجاني',
                'description' => 'الباقة المجانية',
                'price' => 0.00,
            ],
            [
                'name' => 'أساسي',
                'description' => 'الباقة الأساسية',
                'price' => 9.99,
            ],
            [
                'name' => 'متقدم',
                'description' => 'الباقة المتقدمة',
                'price' => 29.99,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::query()->updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
