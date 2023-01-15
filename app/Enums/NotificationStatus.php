<?php

namespace App\Enums;

use Modules\Morphling\Enums\HasSelectOptions;
use Modules\Morphling\Enums\HasValues;

enum NotificationStatus: string
{
    use HasValues, HasSelectOptions;

    case Info = 'info';
    case Success = 'success';
    case Danger = 'danger';
    case Warning = 'warning';
}
