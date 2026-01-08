<?php
namespace App\Traits;

use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;

trait TranslateTrait{

    use HasTranslations;
    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, \App::getLocale());
        }
        return $attributes;
    }
}
