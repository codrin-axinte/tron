<?php

namespace App\Telegram\Commands;

use App\Actions\MLM\ChangePackage;
use DefStudio\Telegraph\Enums\ChatActions;
use Modules\Wallet\Models\PricingPlan;

class UpgradePackageCommand extends TelegramCommand
{
    public function __invoke(?string $packageId = null)
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
            app(ChangePackage::class)->handle($this->currentUser, $package);
            $this->chat->markdown("✅ *Great*! You have changed your plan to $package->name.")->dispatch();

        } catch (\Throwable $exception) {
            $this->chat->markdown('❌ *Something went wrong*. I could not change your plan.')->dispatch();
        }

        $this->start();
    }
}
