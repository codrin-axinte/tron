<?php

namespace Modules\Settings\Nova\Flexible\Layouts;

use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class CustomFieldsLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'custom-field';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Custom Fields';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Text::make('Label')->required(),
            Text::make('Value')->required(),
            Text::make('Format')->required()->withMeta(['value' => '{LABEL}: {VALUE}']),
        ];
    }
}
