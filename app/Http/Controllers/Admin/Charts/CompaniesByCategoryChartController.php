<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Http\Controllers\Admin\Charts\Extended\Chart;
use App\Models\Category;
use App\Models\Company;
use Backpack\CRUD\app\Http\Controllers\ChartController;

/**
 * Class TestChartChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompaniesByCategoryChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();
        $categories = Category::query()->withCount('companies')->get();
        $query = $categories->pluck('companies_count', 'name');
        $values = collect($query)->values();
        $keys = collect($query)->keys() ?? [];

        $colors = ['#0891b2', '#10b981', '#6366f1', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#34d399', '#818cf8', '#fbbf24', '#f87171', '#a78bfa'];
        $bgColors = $values->map(fn($v, $i) => $colors[$i % count($colors)])->toArray();

        $dataset = $this->chart->dataset(__('crud.chart_companies_by_category'), 'bar', $values);
        $dataset->options([
            'labels' => $keys->toArray(),
            'backgroundColor' => $bgColors,
        ]);

        $this->chart->labels($keys);
        $this->chart->load(backpack_url('charts/category-companies'));
    }




}
