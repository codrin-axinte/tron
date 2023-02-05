<?php

namespace App\Services;

use App\Http\Integrations\Tron\Data\Responses\GetAccountResponseData;
use App\Http\Integrations\Tron\Requests\GetAccountRequest;
use App\Http\Integrations\Tron\ValueObjects\TRXBalance;
use App\Models\User;

class TronService
{
    public function checkBalance(User $user)
    {
        $wallet = $user->wallet;
        $pendingAction = $user->pendingActions()
            ->awaitsConfirmation()
            ->latest()
            ->first();

        $price = $pendingAction->meta['price'];
        $plan = $pendingAction->meta['plan'];

        $address = $wallet->address;


        $response = GetAccountResponseData::from(GetAccountRequest::make($address)->send());

        $balance =  TRXBalance::make($response->balance);


    }
}
