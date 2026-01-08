<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Backpack\CRUD\app\Library\Widget;

class DashboardController extends Controller
{

    public function index()
    {
        Widget::add([
            'type' => 'div',
            'class' => 'row',
            'content' => $this->contentForManager()
        ]);
        Widget::add([
            'type' => 'div',
            'class' => 'row border-bottom',
        ]);
        Widget::add([
            'type' => 'div',
            'class' => 'row',
            'content' => $this->Charts()
        ]);
        return view('dashboard');
    }


    private function contentForManager()
    {
        return [
            [
                'type' => 'small_info',
                'color' => 'primary',
                'title' => 'عدد المستخدمين الكلي',
                'count' => User::query()->where('is_admin',false)->count(),
                'icon' => 'users'
            ],
            [
                'type' => 'small_info',
                'color' => 'success',
                'title' => 'المستخدمين الفعالين',
                'count' => User::query()->where('is_admin',false)->where('is_active',true)->count(),
                'icon' => 'users'
            ],
            [
                'type' => 'small_info',
                'color' => 'error',
                'title' => 'المستخدمين الغير الفعالين',
                'count' => User::query()->where('is_admin',false)->where('is_active',false)->count(),
                'icon' => 'users'
            ],
            [
                'type' => 'small_info',
                'color' => 'error',
                'title' => 'مستخدمين يملكون شركات',
                'count' => User::query()->whereHas('companies')->count(),
                'icon' => 'users'
            ],
            [
                'type' => 'small_info',
                'color' => 'primary',
                'title' => 'عدد الشركات',
                'count' => Company::query()->count(),
                'icon' => 'users'
            ],
            [
                'type' => 'small_info',
                'color' => 'primary',
                'title' => 'الشركات التي دفعت',
                'count' => Company::query()->where('has_paid',true)->count(),
                'icon' => 'users'
            ]
        ];

    }

    private function Charts()
    {
        return [
            [
                'type' => 'newChart',
                'controller' => Charts\CompaniesByCategoryChartController::class,
                'class' => 'mb-2',
                'wrapper' => ['class' => 'col-md-10'],
            ]
        ];
    }
}
