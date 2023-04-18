<?php

namespace App\Telegram\Commands\Admin;

use App\Telegram\Commands\TelegramCommand;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Modules\Acl\Enums\GenericPermission;

class AdminToolboxCommand extends TelegramCommand
{
    public function authorized(): bool
    {
        return $this->currentUser?->can(GenericPermission::ViewAdmin->value);
    }

    public function __invoke()
    {
        $this->chat->message('Admin menu')->keyboard($this->menu())->send();
    }

    private function menu()
    {
        return Keyboard::make()->buttons([
            Button::make('🌐 Open admin panel')->url(config('app.url')),
            Button::make('🔨 Sandbox command')->action('test'),
            Button::make('🚀 Self-destroy')->action('dummy'),
            Button::make('⬅️ Back')->action('start'),
        ]);
    }
}
