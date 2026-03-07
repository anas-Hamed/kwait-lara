<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Http\Controllers\Admin\Charts\Extended\Chart;
use App\Models\Company;
use Backpack\CRUD\app\Http\Controllers\ChartController;

class PaymentStatusChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();
        $this->chart->displayAxes(false);

        $paid = Company::where('has_paid', true)->count();
        $unpaid = Company::where('has_paid', false)->count();

        $this->chart->dataset(__('crud.chart_payment_status'), 'doughnut', [$paid, $unpaid])
            ->options([
                'labels' => [__('crud.paid'), __('crud.unpaid')],
                'backgroundColor' => ['#10b981', '#ef4444'],
            ]);
        $this->chart->labels([__('crud.paid'), __('crud.unpaid')]);
        $this->chart->load(backpack_url('charts/payment-status'));
    }
}
