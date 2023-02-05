<?php

namespace App\Actions\Onboarding;


use App\Http\Integrations\Tron\Data\GenerateWalletResponseData;
use App\Http\Integrations\Tron\Requests\GenerateRandomWalletRequest;
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

            $response = GenerateWalletResponseData::from(GenerateRandomWalletRequest::make()->send()->json());

            $user->wallet->update([
                'private_key' => $response->privateKey,
                'public_key' => $response->publicKey,
                'address' => $response->address,
                'mnemonic' => $response->mnemonic,
            ]);

            return $user;
        });
    }
}
