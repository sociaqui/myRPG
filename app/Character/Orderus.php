<?php

namespace Character;


class Orderus extends AbstractGameCharacter
{
    const MIN_HEALTH = 70;
    const MAX_HEALTH = 100;
    const MIN_STRENGTH = 70;
    const MAX_STRENGTH = 80;
    const MIN_DEFENCE = 45;
    const MAX_DEFENCE = 55;
    const MIN_SPEED = 40;
    const MAX_SPEED = 50;
    const MIN_LUCK = 10;
    const MAX_LUCK = 30;

    const NAME = 'Orderus';

    const OFFENSIVE_ABILITIES = ['doubleHit'];
    const DEFENSIVE_ABILITIES = ['halfDamage'];
}
