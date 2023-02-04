<?php

namespace App\Telegram;

use App\Actions\MLM\ChangePackage;
use App\Actions\Onboarding\CreateUserFromTelegram;
use App\Actions\Onboarding\VerifyInvitationCode;
use App\Enums\ChatHooks;
use App\Models\MessageTemplate;
use App\Renderers\PricingPlanRenderer;
use App\Telegram\Traits\ExtendsSetupChat;
use App\Telegram\Traits\HasChatMenus;
use App\Telegram\Traits\HasMessageTemplates;
use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\DTO\User;

use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Keyboard\Button;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Stringable;
use Modules\Wallet\Models\PricingPlan;
use function Symfony\Component\String\b;

class DefaultWebhookHandler extends WebhookHandler
{
    use ExtendsSetupChat, HasMessageTemplates, HasChatMenus;

    /*  protected function handleChatMemberJoined(User $member): void
      {

          if ($member->isBot()) {
              $this->chat->banMember($member->id());
              return;
          }

          $this->chat->message('Hello, ' . $member->username() . '. Please, enter use the command /join <code> to ')->send();
      }*/

    protected function handleChatMessage(Stringable $text): void
    {

    }

    public function test()
    {
        // Debug
        //$this->chat->message('Test')->forceReply('Input your pin card')->send();
    }

    public function start()
    {
        if ($this->isAuth()) {
            $this->showMenu();
            return;
        }

        // Show message template for onboarding
        $this->sendTemplate(ChatHooks::Start);
    }


    public function dummy()
    {
        $this->chat->message('💀 Not implemented yet.')->send();
        $this->start();
    }

    public function myCode()
    {
        if ($this->isGuest()) {
            $this->handleUnknownCommand(new Stringable('myCode'));
            return;
        }

        $user = $this->currentUser;
        $code = $user->referralLink->code;

        $this->chat->action(ChatActions::TYPING)->send();
        $this->chat->markdown("Send this code to your friend to be able to join. \n\nYour code is: $code")
            ->send();
    }


    public function upgrade(?string $packageId = null)
    {
        $package = $this->data->get('package', $packageId);

        $package = PricingPlan::query()->find($package);

        if (!$package) {
            $this->chat->message('You must specify a valid package ID.');
            return;
        }

        //  $this->chat->message('⏳ Changing package to ' . $package->name . '...')->send();

        $this->chat->action(ChatActions::TYPING)->send();

        try {
            app(ChangePackage::class)->handle($this->getCurrentUser(), $package);
            $this->chat->markdown("✅ *Great*! You have changed your plan to $package->name.")->dispatch();

        } catch (\Throwable $exception) {
            $this->chat->markdown('❌ *Something went wrong*. I could not change your plan.')->dispatch();
        }

        $this->start();
    }

    public function packages()
    {
        $user = $this->currentUser;

        $plans = PricingPlan::query()->ordered()->get();
        $currentPlan = $user->subscribedPlan();
        $message = '';
        $renderer = app(PricingPlanRenderer::class);

        $this->chat->action(ChatActions::TYPING)->send();

        foreach ($plans as $plan) {
            $message .= $renderer->render($plan, $currentPlan);
            $message .= "\n\n";
        }

        $keyboard = $this->plansMenu($plans, $currentPlan);

        $this->chat
            ->edit($this->messageId)
            ->markdown($message)
            ->keyboard($keyboard)
            ->dispatch();
    }

    public function team()
    {
        if ($this->isGuest()) {
            $this->handleUnknownCommand(new Stringable('team'));
            return;
        }

        $user = $this->currentUser;
        $team = $user->ownedTeam;
        $members = $user->ownedTeam->members;

        $this->chat->action(ChatActions::TYPING)->send();
        $message = "⭐ **Your team score is: $team->score ({$team->members->count()} members)** \n\n";

        foreach ($members as $member) {
            //$plan = $member->pricingPlans->first()->name ?? 'No plan';
            $message .= '- ' . $member->name . "\n";
        }


        $this->chat->markdown($message)->send();
        $this->start();
    }

    public function join($code)
    {
        if ($this->chat->belongsToMany(\App\Models\User::class, 'chat_user')->exists()) {
            $this->chat->message('🎩 You are already a member of a team. Use the /help command if you are lost.')->send();
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
            $user = $createUser->create($this->chat, $affiliate);
            $this->chat->markdown('🎉Great, account created. Your email is: **' . $user->email)->send() . '**';
        } catch (\Exception $exception) {
            $this->chat->message('💀 Something went wrong. I could not create your account.');
            \Log::error($exception->getMessage(), $exception->getTrace());
        }
    }

    public function help()
    {
        $this->sendTemplate(ChatHooks::Help);
        $this->start();
    }


}
