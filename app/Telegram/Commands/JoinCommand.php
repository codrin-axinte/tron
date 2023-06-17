<?php

namespace App\Telegram\Commands;

use App\Actions\Onboarding\CreateUserFromTelegram;
use App\Actions\Onboarding\VerifyInvitationCode;
use App\Enums\ChatHooks;
use App\Enums\MessageType;
use App\Events\TelegramHook;
use App\Telegram\Renderers\WalletRenderer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JoinCommand extends TelegramCommand
{
    public function __construct(
        private WalletRenderer         $walletRenderer,
        private VerifyInvitationCode   $verifyInvitationCode,
        private CreateUserFromTelegram $createUserFromTelegram)
    {
    }

    public function __invoke($code)
    {

        if ($this->isAuth()) {

            $this->send('🎩 You are already a member of a team. Use the /help command if you are lost.')
                ->start();

            return;
        }

        try {
            $affiliate = $this->verifyInvitationCode->handle($code);

        } catch (ModelNotFoundException $exception) {
            $this->send(
                __("The code ':code' is invalid. Please, try again.", ['code' => $code]),
                MessageType::Error
            )->start();
            return;
        }

        $this->send(
            __("The code '$code' is valid. Please, wait..."),
            MessageType::Success
        );

        try {

            $user = $this->createUserFromTelegram->create($this->chat, $affiliate, $this->message->from());

            //$this->markdown("🎉Great, I have created your account. Now let's invest! 📈")->dispatch();
            $this->send($this->walletRenderer->render($user->wallet));

        } catch (\Throwable $exception) {
            \Log::error($exception->getMessage(), $exception->getTrace());

            $this->send(
                __('Something went wrong on our side. I could not create your account.'),
                MessageType::Error
            )->start();

            return;
        }

    }
}
