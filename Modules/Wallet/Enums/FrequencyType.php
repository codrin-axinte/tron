<?php

namespace Modules\Wallet\Enums;

enum FrequencyType: string
{
    case Once = 'once';
    case Monthly = 'monthly';
    case Yearly = 'yearly';
}
