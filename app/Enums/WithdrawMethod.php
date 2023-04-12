<?php

namespace App\Enums;

enum WithdrawMethod: string
{
    case Approval = 'approval';
    case Semi = 'semi';
    case Automatic = 'automatic';
}
