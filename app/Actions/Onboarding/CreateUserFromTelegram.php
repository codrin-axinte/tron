<?php

namespace App\Actions\Onboarding;


use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphChat;

class CreateUserFromTelegram
{
    /**
     * @throws \Throwable
     */
    public function create(TelegraphChat $chat, User $affiliate): User
    {
        return \DB::transaction(function () use ($chat, $affiliate) {
            $user = User::factory()->withRandomPassword()->createOne();
            $user->chats()->attach($chat);

            $affiliate->ownedTeam->members()->attach($user);

            return $user;
        });
    }
}
