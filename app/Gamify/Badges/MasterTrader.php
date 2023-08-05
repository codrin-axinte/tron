<?php

namespace App\Gamify\Badges;

use QCod\Gamify\BadgeType;

class MasterTrader extends BadgeType
{
    /**
     * Description for badge
     *
     * @var string
     */
    protected $description = 'You are an experienced trader';

    /**
     * Check is user qualifies for badge
     *
     * @param $user
     * @return bool
     */
    public function qualifier($user): bool
    {
        return $user->getPoints() >= 1000;
    }
}
