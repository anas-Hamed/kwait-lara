<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Setting;
use App\Traits\BilingualFieldsTrait;
use Illuminate\Console\Command;

class NormalizeArabicTranslations extends Command
{
    use BilingualFieldsTrait;

    protected $signature = 'translations:normalize-arabic';
    protected $description = 'Re-wrap stored Arabic translations with dir="rtl" on every block element so list markers and punctuation render correctly on the public site.';

    public function handle(): int
    {
        $targets = [
            Setting::class  => ['value'],
            Blog::class     => ['title', 'text'],
            Category::class => ['name'],
        ];

        foreach ($targets as $model => $fields) {
            $this->info("Processing {$model}");
            $count = 0;

            $model::query()->chunkById(200, function ($rows) use ($fields, &$count) {
                foreach ($rows as $row) {
                    $changed = false;
                    foreach ($fields as $field) {
                        $arabic = $row->getTranslation($field, 'ar', false);
                        if ($arabic === '' || $arabic === null) {
                            continue;
                        }
                        $normalized = $this->normalizeArabicHtml($arabic);
                        if ($normalized !== $arabic) {
                            $row->setTranslation($field, 'ar', $normalized);
                            $changed = true;
                        }
                    }
                    if ($changed) {
                        $row->save();
                        $count++;
                    }
                }
            });

            $this->line(" → {$count} rows updated");
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}
