<?php

namespace App\Actions\MLM;

use App\Models\User;

class UpdateUsersWalletsByCurrentBalance
{
    public function __construct(protected UpdateWalletByInterest $updateWalletByInterest)
    {
    }

    public function execute()
    {
        $interest_percentage = 10;

        User::query()
            ->with(['wallet'])
            ->role('trader')
            ->cursor()
            ->each(function (User $user) use ($interest_percentage) {
                $rate = $interest_percentage / 100;
                $this->updateWalletByInterest->execute($user->wallet, $rate);
            });
    }
}
