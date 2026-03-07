<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Http\Controllers\Admin\Charts\Extended\Chart;
use App\Models\Company;
use Backpack\CRUD\app\Http\Controllers\ChartController;

class CompanyGrowthChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        $months = collect();
        $counts = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push($date->translatedFormat('M Y'));
            $counts->push(
                Company::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            );
        }

        $this->chart->dataset(__('crud.chart_new_companies'), 'line', $counts->values())
            ->options([
                'labels' => $months->values()->toArray(),
                'borderColor' => '#6366f1',
                'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                'fill' => true,
                'tension' => 0.4,
            ]);
        $this->chart->labels($months->values());
        $this->chart->load(backpack_url('charts/company-growth'));
    }
}
