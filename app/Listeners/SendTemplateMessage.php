<?php

namespace App\Listeners;

use App\Contracts\SendsMessageTemplates;
use App\Telegram\Traits\HasMessageTemplates;

class SendTemplateMessage
{
    use HasMessageTemplates;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(SendsMessageTemplates $event)
    {
        // TODO: Implement variable binding using Feather

        $this->sendTemplate($event->hooks(), $event->chat());
    }
}
