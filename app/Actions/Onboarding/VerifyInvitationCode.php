<?php

namespace App\Actions\Onboarding;

use App\Models\ReferralLink;
use App\Models\User;

class VerifyInvitationCode
{
    public function handle(string $code): ?User
    {
        $invitation = ReferralLink::with(['user'])->where(['code' => $code])->firstOrFail();

        return $invitation->user;
    }
}
