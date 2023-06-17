<?php

namespace App\Enums;

enum MessageType
{
    case Default;
    case Success;
    case Error;
    case Ask;
}
