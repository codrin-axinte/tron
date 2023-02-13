<?php

namespace App\Listeners;

use App\Contracts\SendsMessageTemplates;
use App\Telegram\Traits\HasMessageTemplates;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param SendsMessageTemplates $event
     * @return void
     */
    public function handle(SendsMessageTemplates $event)
    {
        $this->sendTemplate($event->hooks(), $event->chat());
    }
}
