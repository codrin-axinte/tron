<?php

namespace Modules\Wallet\Transformers;

use App\Http\Resources\ProviderProfileCollectionResource;
use Orion\Http\Resources\Resource;

class WalletTransactionTransformer extends Resource
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
            'author' => $this->whenLoaded('author', fn () => ProviderProfileCollectionResource::make($this->author->providerProfile)),
            'wallet' => $this->whenLoaded('wallet', fn () => WalletTransformer::make($this->wallet)),
            'amount' => $this->amount,
            'type' => $this->type,
            'description' => $this->description,
            'meta' => $this->meta,
            'created_at' => $this->created_at->toFormattedDateString(),
        ];
    }
}
