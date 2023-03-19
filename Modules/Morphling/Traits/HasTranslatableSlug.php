<?php

namespace Modules\Morphling\Traits;

trait HasTranslatableSlug
{
    public function resolveRouteBinding($value, $field = null)
    {
        if ($this->translatable && in_array($field, $this->translatable)) {
            return $this->where($field.'->'.app()->getLocale(), $value)
                ->orWhere($field.'->'.config('app.locale'), $value)
                ->orWhere($field.'->'.config('translatable.fallback_locale'), $value)
                ->firstOrFail();
        } else {
            return parent::resolveRouteBinding($value, $field);
        }
    }
}
