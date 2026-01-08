<?php
namespace App\Http\Controllers\Admin\Charts\Extended;
use ConsoleTVs\Charts\Classes\Chartjs\Chart as BaseChart;
class Chart extends BaseChart{
    /**
     * Chartjs dataset class.
     *
     * @var object
     */
    public $dataset = Dataset::class;
}
