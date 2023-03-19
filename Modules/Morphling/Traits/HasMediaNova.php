<?php

namespace Modules\Morphling\Traits;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Modules\Morphling\Nova\DefaultMediaProperties;

trait HasMediaNova
{
    public function mediaField($label = 'Gallery', $collection = 'default'): Images
    {
        return Images::make($label, $collection)
            ->customPropertiesFields(DefaultMediaProperties::make())
            ->conversionOnIndexView('small')
            ->conversionOnPreview('small')
            ->conversionOnDetailView('medium')
            ->conversionOnForm('medium')
            ->withResponsiveImages()
            ->hideFromIndex();
    }
}
