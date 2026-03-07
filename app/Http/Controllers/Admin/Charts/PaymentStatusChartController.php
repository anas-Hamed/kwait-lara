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

        $this->chart->dataset('حالة الدفع', 'doughnut', [$paid, $unpaid])
            ->options([
                'labels' => ['مدفوع', 'غير مدفوع'],
                'backgroundColor' => ['#10b981', '#ef4444'],
            ]);
        $this->chart->labels(['مدفوع', 'غير مدفوع']);
        $this->chart->load(backpack_url('charts/payment-status'));
    }
}
