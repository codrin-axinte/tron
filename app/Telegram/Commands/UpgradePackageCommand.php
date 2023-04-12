<?php

namespace App\Telegram\Commands;

use App\Actions\Package\CancelPackageConfirmation;
use App\Actions\Package\ChangePackage;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Modules\Wallet\Models\PricingPlan;

/**
 * @deprecated Not in use anymore
 */
class UpgradePackageCommand extends TelegramCommand
{

    public function __invoke(?string $packageId = null)
    {
        $plan = $this->data->get('package', $packageId);

        $plan = PricingPlan::query()->find($plan);

        if (!$plan) {
            $this->sendTyping();
            $this->message(' You must specify a valid plan ID.')->send();
            return;
        }

        $wantsToCancel = $this->data->get('cancel', false);

        if ($wantsToCancel) {
            $this->cancelUpgrade($plan);
            return;
        }

        //  $this->chat->message('⏳ Changing plan to ' . $plan->name . '...')->send();

        $this->sendTyping();

        if ($this->currentUser->pendingActions()
            ->awaitsConfirmation()
            ->where('meta->plan', $plan->id)
            ->exists()) {

            $this->upgradeInProgress($plan);

            return;
        }

        $this->upgrade($plan);
    }


    private function cancelUpgrade(PricingPlan $plan)
    {
        $this->sendTyping();

        try {
            app(CancelPackageConfirmation::class)($this->currentUser, $plan);
            $this
                ->markdown("I have canceled your package upgrade for '$plan->name'. 😔")
                ->send();
            $this->runCommand('packages');

        } catch (\Throwable $exception) {
            $this->error();
            $this->start();
        }
    }

    private function upgradeInProgress(PricingPlan $plan)
    {
        $this
            ->markdown("❓ You have already selected a plan (*$plan->name*) that is awaiting a confirmation. Do you want to change it?")
            ->keyboard(Keyboard::make()->buttons([
                Button::make('✅ Yes, I would like to change')->action('upgrade')
                    ->param('cancel', true)
                    ->param('plan', $plan->id),
                Button::make('❌ No, never mind')->action('start'),
            ]))
            ->send();
    }


    private function upgrade(PricingPlan $plan)
    {
        try {
            app(ChangePackage::class)->handle($this->currentUser, $plan);
            $this->markdown("🎉 *Great*! After the transaction is confirmed I will set your plan to $plan->name.")->dispatch();

        } catch (\Throwable $exception) {
            $this->markdown('💀 *Something went wrong*. I could not change your plan.')->dispatch();
        }

        $this->start();
    }
}
