<?php

namespace Modules\Morphling\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Orion\Http\Resources\Resource;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin JsonResource|Resource|InteractsWithMedia
 */
trait HasMediaTransformer
{
    public function mediaSingleTransformer($collection = 'default')
    {
        return $this->whenLoaded('media',
            fn () => new \Modules\Morphling\Transformers\Media(
                $this->getFirstMedia($collection)
            ));
    }

    public function mediaTransformer($collection = 'default')
    {
        return $this->whenLoaded('media',
            fn () => \Modules\Morphling\Transformers\Media::collection(
                $this->getMedia($collection)
            ));
    }
}
