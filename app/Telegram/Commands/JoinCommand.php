<?php

namespace App\Telegram\Commands;

use App\Actions\Onboarding\CreateUserFromTelegram;
use App\Actions\Onboarding\VerifyInvitationCode;
use App\Enums\ChatHooks;
use App\Events\TelegramHook;
use App\Telegram\Renderers\WalletRenderer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JoinCommand extends TelegramCommand
{
    public function __construct(
        private WalletRenderer $walletRenderer,
        private VerifyInvitationCode $verifyInvitationCode,
        private CreateUserFromTelegram $createUserFromTelegram)
    {
    }

    public function __invoke($code)
    {
        $this->sendTyping();

        if ($this->isAuth()) {
            $this->message('🎩 You are already a member of a team. Use the /help command if you are lost.')->dispatch();
            $this->start();

            return;
        }

        try {
            $affiliate = $this->verifyInvitationCode->handle($code);

        } catch (ModelNotFoundException $exception) {
            $this->message("❌ The code '$code' is invalid. Please, try again.")->dispatch();

            return;
        }

        $this->message("✅ The code '$code' is valid. Please, wait...")->dispatch();

        try {

            $user = $this->createUserFromTelegram->create($this->chat, $affiliate, $this->message->from());
            event(new TelegramHook($user, ChatHooks::Joined));
            //$this->markdown("🎉Great, I have created your account. Now let's invest! 📈")->dispatch();
            $this->markdown($this->walletRenderer->render($user->wallet))->dispatch();

        } catch (\Throwable $exception) {
            $this->markdown('💀 Something went wrong on our side. I could not create your account.')->dispatch();
            \Log::error($exception->getMessage(), $exception->getTrace());

            return;
        }

    }
}
