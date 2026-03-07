<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Http\Controllers\Admin\Charts\Extended\Chart;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\ChartController;

class UserGrowthChartController extends ChartController
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
                User::where('is_admin', false)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            );
        }

        $this->chart->dataset('المستخدمين الجدد', 'line', $counts->values())
            ->options([
                'labels' => $months->values()->toArray(),
                'borderColor' => '#0891b2',
                'backgroundColor' => 'rgba(8, 145, 178, 0.1)',
                'fill' => true,
                'tension' => 0.4,
            ]);
        $this->chart->labels($months->values());
        $this->chart->load(backpack_url('charts/user-growth'));
    }
}
