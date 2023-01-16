<?php

namespace Modules\Morphling\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Laravel\Nova\Menu\Menu;

class BootUserMenu
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Menu $menu, public Request $request)
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
