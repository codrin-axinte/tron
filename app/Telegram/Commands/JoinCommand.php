<?php

namespace App\Telegram\Commands;

use App\Actions\Onboarding\CreateUserFromTelegram;
use App\Actions\Onboarding\VerifyInvitationCode;
use App\Telegram\Renderers\WalletRenderer;
use DefStudio\Telegraph\Enums\ChatActions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JoinCommand extends TelegramCommand
{
    public function __construct(private WalletRenderer $walletRenderer)
    {
    }

    public function __invoke($code)
    {
        if ($this->isAuth()) {
            $this->message('🎩 You are already a member of a team. Use the /help command if you are lost.')->send();
            $this->start();
            return;
        }

        $verifyInvitationCode = app(VerifyInvitationCode::class);
        $createUser = app(CreateUserFromTelegram::class);

        try {
            $affiliate = $verifyInvitationCode->handle($code);
            $this->sendTyping();
        } catch (ModelNotFoundException $exception) {
            $this->message("❌ The code '$code' is invalid. Please, try again.")->send();
            return;
        }

        $this->message("✅ The code '$code' is valid. Please, wait...")->send();
        $this->sendTyping();

        try {

            $user = $createUser->create($this->chat, $affiliate, $this->message->from());
            $this->markdown("🎉Great, I have created your account. Now let's invest! 📈")->send();
            $this->markdown($this->walletRenderer->render($user->wallet))->send();

        } catch (\Throwable $exception) {
            $this->chat->message('💀 Something went wrong on our side. I could not create your account.')->send();
            \Log::error($exception->getMessage(), $exception->getTrace());
            return;
        }

    }
}
