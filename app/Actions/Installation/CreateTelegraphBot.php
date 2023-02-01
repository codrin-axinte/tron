<?php

namespace App\Actions\Installation;

use Closure;
use DefStudio\Telegraph\Models\TelegraphBot;

class CreateTelegraphBot implements InstallPipeContract
{
    public function handle($payload, Closure $next)
    {
        $bot = TelegraphBot::create([
            'token' => config('services.telegram.token'),
            'name' => config('services.telegram.name'),
        ]);

        /** @var TelegraphBot $bot */
        $bot->registerWebhook()->send();

        return $next($payload);
    }
}
