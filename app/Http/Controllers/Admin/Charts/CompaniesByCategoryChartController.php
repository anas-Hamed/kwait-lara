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
        $query = $categories->pluck('companies_count','name');
        $this->chart->dataset('عدد الشركات في كل تصنيف','bar',collect($query)->values())->labels(collect($query)->keys()??[]);
        $this->chart->labels(collect($query)->keys()??[]);
        $this->chart->load(backpack_url('charts/category-companies'));
    }




}
