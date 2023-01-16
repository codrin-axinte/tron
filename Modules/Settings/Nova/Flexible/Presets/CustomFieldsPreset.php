<?php

namespace Modules\Settings\Nova\Flexible\Presets;

use Modules\Settings\Nova\Flexible\Layouts\CustomFieldsLayout;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class CustomFieldsPreset extends Preset
{
    /**
     * @throws \Exception
     */
    public function handle(Flexible $field)
    {
        $field->button(__('Add custom field'));
        $field->confirmRemove();
        $field->fullWidth();
        $field->collapsed();
        $field->addLayout(CustomFieldsLayout::class);
    }
}
