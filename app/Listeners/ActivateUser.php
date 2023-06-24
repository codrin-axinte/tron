<?php

namespace App\Listeners;

use App\Actions\Tron\UserActivateAction;
use App\Events\BlockchainTopUp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Acl\Services\AclService;

class ActivateUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private UserActivateAction $action)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BlockchainTopUp $event
     * @return void
     */
    public function handle(BlockchainTopUp $event): void
    {
        $user = $event->user;

        if (!$user->hasAnyRole([AclService::trader()])) {
            $this->action->run($user);
        }
    }
}
