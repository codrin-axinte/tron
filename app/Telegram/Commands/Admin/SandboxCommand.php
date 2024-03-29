<?php

namespace App\Telegram\Commands\Admin;

use App\Telegram\Commands\TelegramCommand;
use DefStudio\Telegraph\Exceptions\KeyboardException;

class SandboxCommand extends TelegramCommand
{
    /**
     * @throws KeyboardException
     */
    public function __invoke()
    {
        // Debug

        //$this->chat->message('Test')->forceReply('Input your pin card')->send();
        $response = $this->markdown('Hello')
            ->forceReply(placeholder: 'Enter your code...')
            ->send();

        if ($response->successful()) {
//            \Log::debug('Response Telegram', [
//                'id' => $response->telegraphMessageId(),
//                'json' => $response->json(),
//                'collect' => $response->collect(),
//            ]);
        }
    }

    public function authorized(): bool
    {
        return app()->environment('local');
    }
}
