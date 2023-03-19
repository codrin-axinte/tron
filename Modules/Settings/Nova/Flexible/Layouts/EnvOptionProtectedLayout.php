<?php

namespace Modules\Settings\Nova\Flexible\Layouts;

use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class EnvOptionProtectedLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'env-protected-option-field';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Env Protected Option';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Text::make('Key')->required(),
            Password::make('Value')->nullable(),
        ];
    }
}
