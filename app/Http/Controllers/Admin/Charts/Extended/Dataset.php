<?php
namespace App\Http\Controllers\Admin\Charts\Extended;
use ConsoleTVs\Charts\Classes\Chartjs\Dataset as BaseDataset;
use Illuminate\Support\Collection;

class Dataset extends BaseDataset{


    /**
     * Set the chart labels.
     *
     * @param array|Collection $labels
     *
     * @return Dataset
     */
    public function labels($labels)
    {
        if ($labels instanceof Collection) {
            $labels = $labels->toArray();
        }

        $this->options['labels'] = $labels;

        return $this;
    }
}
