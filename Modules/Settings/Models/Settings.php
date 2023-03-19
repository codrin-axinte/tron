<?php

namespace Modules\Settings\Models;

use Outl1ne\NovaSettings\NovaSettings;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Settings extends \Outl1ne\NovaSettings\Models\Settings
{
    use HasFlexible;

    public function setValueAttribute($value)
    {
        $originalCasts = $this->casts;
        $this->casts = NovaSettings::getCasts();
        $value = is_array($value) ? json_encode($value) : $value;

        if ($this->hasCast($this->key)) {
            $value = match ($this->casts[$this->key]) {
                'encrypted' => \Crypt::encryptString($value),
                default => $this->castAttribute($this->key, $value),
            };
        }

        $this->casts = $originalCasts;

        $this->attributes['value'] = $value;
    }
}
