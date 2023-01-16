<?php

namespace Modules\Morphling\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Spatie\MediaLibrary\MediaCollections\Models\Media
 */
class Media extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'url' => $this->getFullUrl(),
            'alt' => $this->getCustomProperty('alt'),
            'title' => $this->file_name,
            'srcset' => $this->getSrcset(),
            'placeholder' => $this->responsiveImages()->getPlaceholderSvg(),
            'type' => $this->getTypeFromExtension(),
        ];
    }
}
