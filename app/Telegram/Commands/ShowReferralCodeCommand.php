<?php

namespace App\Telegram\Commands;

use DefStudio\Telegraph\Enums\ChatActions;
use Illuminate\Support\Stringable;

class ShowReferralCodeCommand extends TelegramCommand
{
    public function authorized(): bool
    {
        return $this->isAuth();
    }

    public function __invoke()
    {
        $user = $this->currentUser;
        $code = $user->referralLink->code;

        $this->chat->action(ChatActions::TYPING)->send();
        $this->chat->markdown("Send this code to your friend to be able to join. \n\nYour code is: $code")
            ->send();
    }
}
