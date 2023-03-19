<?php

namespace Modules\Settings\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Settings\Services\SettingsService;

class BootSettingsPage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public SettingsService $service)
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
