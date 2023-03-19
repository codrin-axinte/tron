<?php

namespace Modules\Wallet\Contracts;

interface IConsumesCredits
{
    public function getPriceInCredits(): int;
}
