<?php

namespace Modules\Wallet\Nova\Layouts;

use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class FeatureLayout extends Layout
{
    protected $title = 'Feature';

    protected $name = 'feature';

    public function fields(): array
    {
        return [
            Text::make(__('Description'), 'description'),
        ];
    }
}
