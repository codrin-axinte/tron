<?php

namespace App\Telegram\Commands;

use App\Enums\MessageType;
use DefStudio\Telegraph\Enums\ChatActions;
use Modules\Settings\Services\SettingsService;
use Str;

class TeamCommand extends TelegramCommand
{
    public function authorized(): bool
    {
        return $this->isAuth();
    }


    public function __invoke()
    {
        $user = $this->currentUser;

        if (!$user) {
            return $this->send(__('messages.error'), MessageType::Error)
                ->start();
        }

        $members = $user->team->members;

        app(SettingsService::class)->getJson('commissions', []);
        $commissions = json_decode(nova_get_setting('commissions', []));

        $total = 0;
        $tree = '';
        $this->renderTree($tree, $members, count($commissions), $total);

        $message = __("⭐ **Your team (:members members): **", ['members' => $total]);
        $message .= "\n\n";
        $message .= $tree;


        return $this->send($message)->start();
    }


    protected function renderTree(&$message, $members, $maxDepth, &$count, $depth = 1): void
    {
        if ($depth > $maxDepth) {
            return;
        }

        $symbol = $depth === 1 ? '-' : '|\_';

        foreach ($members as $member) {
            $count++;
            $spaces = Str::repeat(' ', $depth * 2);
            $message .= "$spaces $symbol " . $member->name . "\n";

            $subMembers = $member->team->members;

            $this->renderTree($message, $subMembers, $maxDepth, $count, $depth + 1);
        }
    }
}
