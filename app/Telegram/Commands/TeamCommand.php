<?php

namespace App\Telegram\Commands;

use DefStudio\Telegraph\Enums\ChatActions;
use Illuminate\Support\Stringable;

class TeamCommand extends TelegramCommand
{
    public function authorized(): bool
    {
        return $this->isAuth();
    }

    public function __invoke()
    {
        $user = $this->currentUser;
        $team = $user->ownedTeam;
        $members = $user->ownedTeam->members;

        $this->chat->action(ChatActions::TYPING)->send();
        $message = "⭐ **Your team score is: $team->score ({$team->members->count()} members)** \n\n";

        foreach ($members as $member) {
            $plan = $member->pricingPlans->first()->name ?? 'No plan';
            $message .= '|_ ' . $member->name . "($plan}) \n";
        }


        $this->chat->markdown($message)->send();
        $this->start();
    }
}
