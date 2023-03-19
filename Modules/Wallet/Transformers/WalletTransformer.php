<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payments\Settings\PaymentsSettings;

class WalletTransformer extends JsonResource
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
            'amount' => $this->amount,
            'name' => nova_get_setting(PaymentsSettings::VIRTUAL_CURRENCY_NAME->value),
        ];
    }
}
