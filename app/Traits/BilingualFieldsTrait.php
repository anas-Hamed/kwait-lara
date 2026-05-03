<?php

namespace App\Traits;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait BilingualFieldsTrait
{
    /**
     * Render two side-by-side inputs (Arabic + English) for a translatable attribute.
     *
     * @param string $name      The translatable attribute name on the model (e.g. 'title')
     * @param string $label     Human-readable label (e.g. 'Title')
     * @param string $type      Backpack field type ('text', 'tinymce', 'textarea', ...)
     * @param array  $overrides Extra field config merged into both locale fields
     */
    protected function addBilingualField(string $name, string $label, string $type = 'text', array $overrides = []): void
    {
        $entry = null;
        $operation = $this->crud->getCurrentOperation();
        if ($operation === 'update') {
            $entry = $this->crud->getCurrentEntry();
        }

        $this->crud->addField([
            'name'  => "__bilingual_header_{$name}",
            'type'  => 'custom_html',
            'value' => '<div class="bilingual-field-header">' . e($label) . '</div>',
        ]);

        foreach (['ar', 'en'] as $locale) {
            $direction = $locale === 'ar' ? 'rtl' : 'ltr';
            $tag = $locale === 'ar' ? 'العربية' : 'English';

            $field = array_merge([
                'name'              => "{$name}_{$locale}",
                'label'             => "{$label} ({$tag})",
                'type'              => $type,
                'fake'              => true,
                'attributes'        => ['dir' => $direction],
                'wrapperAttributes' => [
                    'class' => 'col-md-6 form-group bilingual-field bilingual-field-' . $locale,
                    'dir'   => $direction,
                ],
            ], $overrides);

            if ($entry) {
                $field['value'] = $entry->getTranslation($name, $locale, false);
            }

            $this->crud->addField($field);
        }
    }

    /**
     * Read the *_ar / *_en POST fields and persist them as translations on the entry.
     *
     * @param \Illuminate\Database\Eloquent\Model $entry
     * @param string[]                            $fields Translatable attribute names
     */
    protected function saveBilingualTranslations($entry, array $fields): void
    {
        $request = $this->crud->getRequest();

        foreach ($fields as $name) {
            foreach (['ar', 'en'] as $locale) {
                $value = $request->input("{$name}_{$locale}") ?? '';

                if ($locale === 'ar') {
                    $value = $this->normalizeArabicHtml($value);
                }

                $entry->setTranslation($name, $locale, $value);
            }
        }

        $entry->save();
    }

    /**
     * Strip LTR-leaning artifacts the editor may have injected, then force
     * RTL on every block-level element so the public site renders Arabic
     * correctly regardless of how the frontend wraps the content.
     */
    protected function normalizeArabicHtml(string $value): string
    {
        if ($value === '') {
            return $value;
        }

        $value = preg_replace('/\s(dir|align)\s*=\s*"[^"]*"/i', '', $value);
        $value = preg_replace("/\s(dir|align)\s*=\s*'[^']*'/i", '', $value);
        $value = preg_replace('/text-align\s*:\s*[^;"\']+;?/i', '', $value);
        $value = preg_replace('/direction\s*:\s*[^;"\']+;?/i', '', $value);
        $value = preg_replace('/style\s*=\s*"\s*"/i', '', $value);
        $value = preg_replace("/style\s*=\s*'\s*'/i", '', $value);

        // Inject dir="rtl" on every block-level tag so list markers, headings,
        // and paragraphs render right-aligned even if the parent container is LTR.
        $blockTags = 'p|div|h[1-6]|ol|ul|li|blockquote|table|thead|tbody|tr|td|th|section|article|figure|pre|hr';
        $value = preg_replace_callback(
            '/<(' . $blockTags . ')(\s[^>]*)?>/i',
            function ($m) {
                $tag = $m[1];
                $attrs = $m[2] ?? '';
                if (stripos($attrs, 'dir=') !== false) {
                    return $m[0];
                }
                return '<' . $tag . $attrs . ' dir="rtl">';
            },
            $value
        );

        if (preg_match('/<[a-z][\s\S]*>/i', $value)) {
            $value = '<div dir="rtl" style="text-align:right; direction:rtl;">' . trim($value) . '</div>';
        }

        return $value;
    }
}
