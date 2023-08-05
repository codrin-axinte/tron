<?php

namespace App\Gamify\Badges;

use QCod\Gamify\BadgeType;

class VIP extends BadgeType
{
    /**
     * Description for badge
     *
     * @var string
     */
    protected $description = 'You are a good trader and a valuable part of the community';

    /**
     * Check is user qualifies for badge
     *
     * @param $user
     * @return bool
     */
    public function qualifier($user)
    {
        return $user->getPoints() >= 500;
    }
}
