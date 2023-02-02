<?php

namespace App\Telegram;

use App\Actions\Onboarding\CreateUserFromTelegram;
use App\Actions\Onboarding\VerifyInvitationCode;
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

class DefaultWebhookHandler extends WebhookHandler
{

    private ?\App\Models\User $currentUser = null;

    /*  protected function handleChatMemberJoined(User $member): void
      {

          if ($member->isBot()) {
              $this->chat->banMember($member->id());
              return;
          }

          $this->chat->message('Hello, ' . $member->username() . '. Please, enter use the command /join <code> to ')->send();
      }*/

    /*  protected function handleChatMessage(Stringable $text): void
      {
          //
      }*/


    public function start()
    {
        $response = $this->chat->markdown("*hi*")->send();

        if ($response == "Failed") {
            $response->dump();
        }
    }

    public function dummy()
    {
        $this->chat->message('💀 Not implemented yet.')->send();
    }

    public function myCode()
    {
        $user = $this->getCurrentUser();
        $code = $user->referralLink->code;

        $this->chat->action(ChatActions::TYPING)->send();
        $this->chat->markdown("Send this code to your friend to be able to join. \n\nYour code is: $code")
            ->send();
    }


    public function upgrade(string $packageId)
    {
        $this->dummy();
    }

    public function packages()
    {
        $plans = PricingPlan::query()->ordered()->get();

        $keyboard = Keyboard::make()
            ->buttons(
                $plans->map(
                    fn($plan) => Button::make($plan->name . ' ' . $plan->price . ' TRX')
                        ->action('upgrade')
                        ->param('packageId', $plan->id)
                )->toArray());

        $this->chat->message('Choose a package')
            ->keyboard($keyboard)->send();
    }

    public function team()
    {
        $user = $this->getCurrentUser();

        $team = $user->ownedTeam;
        $members = $user->ownedTeam->members;

        $this->chat->action(ChatActions::TYPING)->send();
        $message = "**Your team score is: $team->score ({$team->members->count()} members)** \n\n";

        foreach ($members as $member) {
            //$plan = $member->pricingPlans->first()->name ?? 'No plan';
            $message .= '- ' . $member->name . "\n";
        }

        $this->chat->markdown($message)->send();
    }

    public function menu()
    {
        $user = $this->getCurrentUser();
        $wallet = $user->wallet;
        $plan = $user->subscribedPlan() ?? 'No Plan';

        $menu = Keyboard::make()
            ->row([
                Button::make("Balance: {$wallet->amount} TRX ($plan->name)")->action('dummy')->width(1),
            ])
            ->row([
                Button::make('💳 Deposit')->action('dummy'),
                Button::make('💵 Withdraw')->action('dummy'),
            ])
            ->row([
                Button::make('⚡ Upgrade package')->action('packages'),
                Button::make('📈 Stats')->action('dummy'),
            ])
            ->row([
                Button::make('🔗 Referral code')->action('myCode'),
                Button::make('👥 My team')->action('team'),
            ])
            ->row([
                //Button::make('👑 Leaderboard')->action('dummy'),
                Button::make('ℹ️ Support')->action('help'),
            ]);


        $this->chat->message('Choose an option')->keyboard($menu)->send();
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


        /* $response = $this->chat->message('What is your invitation code?')
             ->replyKeyboard(
                 ReplyKeyboard::make()->button('Text'),
             )->send();*/


        //$this->chat->message('received')->removeReplyKeyboard()->send();
        /* if ($code == "404") {
             $this->chat->markdown("Verified")->send();
         } else {
             $this->chat->markdown("Not good")->send();
         }*/
    }

    private function getCurrentUser(): ?\App\Models\User
    {
        if (!$this->currentUser) {
            $this->currentUser = $this->chat
                ->belongsToMany(\App\Models\User::class, 'chat_user')
                ->with([
                    'wallet',
                ])->first();
        }

        return $this->currentUser;
    }


    public function help()
    {
        $this->chat->message('This is the help menu')->send();
    }

    public function test()
    {
        $this->chat->message('hello world')
            ->keyboard(Keyboard::make()->buttons([
                Button::make('open')->url('https://test.it'),
            ]))->send();
    }
}
