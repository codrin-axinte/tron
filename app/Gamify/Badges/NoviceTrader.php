<?php

namespace App\Gamify\Badges;

use QCod\Gamify\BadgeType;

class NoviceTrader extends BadgeType
{
    /**
     * Description for badge
     *
     * @var string
     */
    protected $description = 'You are getting the ropes of trading';

    /**
     * Check is user qualifies for badge
     *
     * @param $user
     * @return bool
     */
    public function qualifier($user)
    {
        return $user->getPoints() >= 100;
    }
}
