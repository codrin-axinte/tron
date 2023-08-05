<?php

namespace App\Gamify\Badges;

use QCod\Gamify\BadgeType;

class PopularGuy extends BadgeType
{
    /**
     * Description for badge
     *
     * @var string
     */
    protected $description = 'You know a lot of people and have built a big team.';

    /**
     * Check is user qualifies for badge
     *
     * @param $user
     * @return bool
     */
    public function qualifier($user)
    {
        return $user->ownedTeam->count() >= 100; // 100 direct team members
    }
}
