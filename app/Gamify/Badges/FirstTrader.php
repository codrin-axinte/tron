<?php

namespace App\Gamify\Badges;

use QCod\Gamify\BadgeType;

class FirstTrader extends BadgeType
{
    /**
     * Description for badge
     *
     * @var string
     */
    protected $description = 'The first trader ever to join';

    /**
     * Check is user qualifies for badge
     *
     * @param $user
     * @return bool
     */
    public function qualifier($user)
    {
        return $user->getPoints() >= 1000;
    }
}
