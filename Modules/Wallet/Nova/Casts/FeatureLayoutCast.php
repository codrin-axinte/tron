<?php

namespace Modules\Wallet\Nova\Casts;

use Modules\Wallet\Nova\Layouts\FeatureLayout;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class FeatureLayoutCast extends FlexibleCast
{
    protected $layouts = [
        'feature' => FeatureLayout::class,
    ];
}
