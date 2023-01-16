<?php

namespace Modules\Settings\Listeners;

use Modules\Settings\Events\BootSettingsPage;
use Modules\Settings\Pages\MailSettingsPage;

class RegisterPagesListener
{
    public function __invoke(BootSettingsPage $event): array
    {
        return [
            new MailSettingsPage(),
        ];
    }
}
