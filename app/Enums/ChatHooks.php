<?php

namespace App\Enums;

use Modules\Morphling\Enums\HasSelectOptions;
use Modules\Morphling\Enums\HasValues;

enum ChatHooks: string
{
    use HasValues, HasSelectOptions;

    case Start = 'start';
    case Joined = 'joined';
    case Activated = 'activated';
    case Upgraded = 'upgraded';
    case Downgraded = 'downgraded';
    case MemberJoined = 'member_joined';
    case MemberLeft = 'member_left';
    case WalletUpdated = 'wallet_updated';
    case Help = 'help';
}
