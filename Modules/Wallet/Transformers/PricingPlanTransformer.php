<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Wallet\Enums\FrequencyType;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class PricingPlanTransformer extends JsonResource
{
    use HasFlexible;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'price' => $this->price,
            'description' => $this->description,
            'is_best' => $this->is_best,
            'frequency_type' => $this->frequency_type ?? FrequencyType::Once->value,
            'expires_at' => $this->expires_at,
            'features' => $this->featuresList,
        ];
    }
}
