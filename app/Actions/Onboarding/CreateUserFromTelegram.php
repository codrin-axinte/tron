<?php

namespace App\Actions\Onboarding;


use App\Http\Integrations\Tron\Data\Responses\GenerateWalletResponseData;
use App\Http\Integrations\Tron\Requests\GenerateRandomWalletRequest;
use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphChat;

class CreateUserFromTelegram
{
    /**
     * @throws \Throwable
     */
    public function create(TelegraphChat $chat, User $affiliate, \DefStudio\Telegraph\DTO\User $from): ?User
    {
        return \DB::transaction(function () use ($chat, $affiliate, $from) {
            $user = User::factory()->withRandomPassword()->createOne([
                'name' => $from->firstName() . ' ' . $from->lastName(),
                'username' => $from->username(),
                'telegram_id' => $from->id(),
                'chat_id' => $chat->id,
            ]);

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
