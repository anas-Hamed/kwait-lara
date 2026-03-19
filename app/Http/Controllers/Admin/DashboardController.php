<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyTrustRequest;
use App\Models\CompanyUpdate;
use App\Models\ContactUs;
use App\Models\User;
use Backpack\CRUD\app\Library\Widget;

class DashboardController extends Controller
{
    public function index()
    {
        // Row 1: Stat Cards
        Widget::add([
            'type' => 'div',
            'class' => 'row stat-card-row',
            'content' => $this->statCards(),
        ]);

        // Row 2: Top Charts (Categories bar + Payment doughnut)
        Widget::add([
            'type' => 'div',
            'class' => 'row',
            'content' => [
                [
                    'type' => 'newChart',
                    'controller' => Charts\CompaniesByCategoryChartController::class,
                    'class' => 'card chart-card',
                    'content' => ['header' => __('dashboard.companies_by_category')],
                    'wrapper' => ['class' => 'col-md-8 mb-4'],
                ],
                [
                    'type' => 'newChart',
                    'controller' => Charts\PaymentStatusChartController::class,
                    'class' => 'card chart-card',
                    'content' => ['header' => __('dashboard.payment_status')],
                    'wrapper' => ['class' => 'col-md-4 mb-4'],
                ],
            ],
        ]);

        // Row 3: Growth Charts
        Widget::add([
            'type' => 'div',
            'class' => 'row',
            'content' => [
                [
                    'type' => 'newChart',
                    'controller' => Charts\UserGrowthChartController::class,
                    'class' => 'card chart-card',
                    'content' => ['header' => __('dashboard.user_growth')],
                    'wrapper' => ['class' => 'col-md-6 mb-4'],
                ],
                [
                    'type' => 'newChart',
                    'controller' => Charts\CompanyGrowthChartController::class,
                    'class' => 'card chart-card',
                    'content' => ['header' => __('dashboard.company_growth')],
                    'wrapper' => ['class' => 'col-md-6 mb-4'],
                ],
            ],
        ]);

        // Row 4: Action Panels
        Widget::add([
            'type' => 'div',
            'class' => 'row',
            'content' => [
                [
                    'type' => 'pending_actions',
                    'wrapper' => ['class' => 'col-md-4 mb-4'],
                    'items' => [
                        [
                            'label' => __('dashboard.pending_company_updates'),
                            'count' => CompanyUpdate::count(),
                            'url' => backpack_url('company-update'),
                            'icon' => 'la-sync',
                            'color' => 'warning',
                        ],
                        [
                            'label' => __('dashboard.pending_trust_requests'),
                            'count' => CompanyTrustRequest::count(),
                            'url' => backpack_url('company-trust-request'),
                            'icon' => 'la-shield-alt',
                            'color' => 'info',
                        ],
                        [
                            'label' => __('dashboard.unread_messages'),
                            'count' => ContactUs::whereNull('read_at')->count(),
                            'url' => backpack_url('contactus'),
                            'icon' => 'la-envelope',
                            'color' => 'danger',
                        ],
                        [
                            'label' => __('dashboard.unpaid_companies'),
                            'count' => Company::where('has_paid', false)->count(),
                            'url' => backpack_url('company'),
                            'icon' => 'la-money-bill',
                            'color' => 'warning',
                        ],
                    ],
                ],
                [
                    'type' => 'recent_activity',
                    'wrapper' => ['class' => 'col-md-4 mb-4'],
                    'activities' => $this->getRecentActivities(),
                ],
                [
                    'type' => 'quick_actions',
                    'wrapper' => ['class' => 'col-md-4 mb-4'],
                    'actions' => [
                        ['label' => __('dashboard.add_company'), 'url' => backpack_url('company/create'), 'icon' => 'la-plus-circle', 'color' => 'primary'],
                        ['label' => __('dashboard.send_notification'), 'url' => backpack_url('notification/create'), 'icon' => 'la-bell', 'color' => 'info'],
                        ['label' => __('dashboard.view_messages'), 'url' => backpack_url('contactus'), 'icon' => 'la-envelope', 'color' => 'warning'],
                        ['label' => __('dashboard.manage_categories'), 'url' => backpack_url('category'), 'icon' => 'la-list-ol', 'color' => 'success'],
                        ['label' => __('dashboard.write_blog'), 'url' => backpack_url('blog/create'), 'icon' => 'la-pen', 'color' => 'danger'],
                    ],
                ],
            ],
        ]);

        return view('dashboard');
    }

    private function statCards(): array
    {
        $totalUsers = User::where('is_admin', false)->count();
        $activeUsers = User::where('is_admin', false)->where('is_active', true)->count();
        $totalCompanies = Company::count();
        $featuredCompanies = Company::where('is_featured', true)->count();
        $paidCompanies = Company::where('has_paid', true)->count();
        $avgRating = round(Company::avg('average_rate') ?? 0, 1);

        // Trend: this month vs last month
        $usersThisMonth = User::where('is_admin', false)
            ->where('created_at', '>=', now()->startOfMonth())->count();
        $usersLastMonth = User::where('is_admin', false)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();
        $userTrend = $usersLastMonth > 0
            ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100)
            : ($usersThisMonth > 0 ? 100 : 0);

        $companiesThisMonth = Company::where('created_at', '>=', now()->startOfMonth())->count();
        $companiesLastMonth = Company::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $companyTrend = $companiesLastMonth > 0
            ? round((($companiesThisMonth - $companiesLastMonth) / $companiesLastMonth) * 100)
            : ($companiesThisMonth > 0 ? 100 : 0);

        return [
            [
                'type' => 'stat_card',
                'gradient' => 'gradient-1',
                'title' => __('dashboard.total_users'),
                'count' => number_format($totalUsers),
                'icon' => 'users',
                'trend' => ($userTrend >= 0 ? '+' : '') . $userTrend . '%',
                'trend_up' => $userTrend >= 0,
                'wrapper' => ['class' => 'col-6 col-lg-2'],
            ],
            [
                'type' => 'stat_card',
                'gradient' => 'gradient-2',
                'title' => __('dashboard.active_users'),
                'count' => number_format($activeUsers),
                'icon' => 'user-check',
                'wrapper' => ['class' => 'col-6 col-lg-2'],
            ],
            [
                'type' => 'stat_card',
                'gradient' => 'gradient-3',
                'title' => __('dashboard.total_companies'),
                'count' => number_format($totalCompanies),
                'icon' => 'building',
                'trend' => ($companyTrend >= 0 ? '+' : '') . $companyTrend . '%',
                'trend_up' => $companyTrend >= 0,
                'wrapper' => ['class' => 'col-6 col-lg-2'],
            ],
            [
                'type' => 'stat_card',
                'gradient' => 'gradient-4',
                'title' => __('dashboard.featured_companies'),
                'count' => number_format($featuredCompanies),
                'icon' => 'star',
                'wrapper' => ['class' => 'col-6 col-lg-2'],
            ],
            [
                'type' => 'stat_card',
                'gradient' => 'gradient-2',
                'title' => __('dashboard.paid_companies'),
                'count' => number_format($paidCompanies),
                'icon' => 'credit-card',
                'wrapper' => ['class' => 'col-6 col-lg-2'],
            ],
            [
                'type' => 'stat_card',
                'gradient' => 'gradient-6',
                'title' => __('dashboard.avg_rating'),
                'count' => $avgRating,
                'icon' => 'star-half-alt',
                'wrapper' => ['class' => 'col-6 col-lg-2'],
            ],
        ];
    }

    private function getRecentActivities(): array
    {
        $activities = collect();

        User::where('is_admin', false)->latest()->take(5)->get()
            ->each(function ($user) use ($activities) {
                $activities->push([
                    'type' => 'user',
                    'description' => __('dashboard.new_user') . ': ' . $user->name,
                    'time' => $user->created_at,
                    'color' => 'success',
                ]);
            });

        Company::latest()->take(5)->get()
            ->each(function ($company) use ($activities) {
                $activities->push([
                    'type' => 'company',
                    'description' => __('dashboard.new_company') . ': ' . $company->ar_name,
                    'time' => $company->created_at,
                    'color' => 'primary',
                ]);
            });

        ContactUs::latest()->take(5)->get()
            ->each(function ($message) use ($activities) {
                $activities->push([
                    'type' => 'message',
                    'description' => __('dashboard.new_message') . ': ' . $message->name,
                    'time' => $message->created_at,
                    'color' => 'warning',
                ]);
            });

        return $activities->sortByDesc('time')->take(10)->values()->toArray();
    }
}
