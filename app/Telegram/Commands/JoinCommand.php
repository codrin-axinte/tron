<?php

namespace App\Telegram\Commands;

use App\Actions\Onboarding\CreateUserFromTelegram;
use App\Actions\Onboarding\VerifyInvitationCode;
use App\Telegram\Renderers\WalletRenderer;
use DefStudio\Telegraph\Enums\ChatActions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JoinCommand extends TelegramCommand
{
    public function __invoke($code)
    {
        if ($this->isAuth()) {
            $this->chat->message('🎩 You are already a member of a team. Use the /help command if you are lost.')->send();
            $this->start();
            return;
        }

        $verifyInvitationCode = app(VerifyInvitationCode::class);
        $createUser = app(CreateUserFromTelegram::class);

        try {
            $affiliate = $verifyInvitationCode->handle($code);
            $this->chat->action(ChatActions::TYPING)->send();
        } catch (ModelNotFoundException $exception) {
            $this->chat->message("❌ The code '$code' is invalid. Please, try again.")->send();
            return;
        }

        try {
            //$this->chat->message('You were invited by: ' . $affiliate->name)->send();
            $this->chat->message("✅ The code '$code' is valid. Please, wait...")->send();
            $this->chat->action(ChatActions::TYPING)->send();

            $user = $createUser->create($this->chat, $affiliate, $this->message->from());

            $this->chat->markdown("🎉Great, I have created your account. Now you have to choose a package.")->send();
            $this->call('packages');
          //  $this->start();

        } catch (\Throwable $exception) {
            $this->chat->message('💀 Something went wrong on our side. I could not create your account.')->send();
            \Log::error($exception->getMessage(), $exception->getTrace());
        }
    }
}
