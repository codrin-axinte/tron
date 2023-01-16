<?php

namespace Modules\Settings\Nova\Flexible\Presets;

use Modules\Settings\Nova\Flexible\Layouts\EnvOptionLayout;
use Modules\Settings\Nova\Flexible\Layouts\EnvOptionProtectedLayout;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class EnvOptionsPreset extends Preset
{
    public function handle(Flexible $field)
    {
        $field->nullable();
        $field->button(__('Add option'));
        $field->confirmRemove();
        $field->fullWidth();
        $field->collapsed();
        $field->addLayout(EnvOptionLayout::class);
        $field->addLayout(EnvOptionProtectedLayout::class);
    }
}
