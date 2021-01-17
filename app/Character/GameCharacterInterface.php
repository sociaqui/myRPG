<?php

namespace Character;


interface GameCharacterInterface
{
    const OFFENSIVE_SKILLS = [
        'doubleHit' => [
            'name' => 'Rapid Strike',
            'description' => 'Strike twice during a single attack; there\'s a 10% chance to use this skill on every attack',
            'catchphrase' => '"If at first you don\'t succeed, hit them again."',
            'chance' => 10,
        ],
    ];
    const DEFENSIVE_SKILLS = [
        'dodge' => [
            'name' => 'dodge',
            'description' => 'Every character has this basic skill; take no damage from an enemy attack;
            there’s a chance based on luck to use this skill on every defence',
            'catchphrase' => '"Now you see me, now you don\'t."',
            'chance' => 'luck_based',
        ],
        'halfDamage' => [
            'name' => 'Magic shield',
            'description' => 'Take only half of the usual damage when an enemy attacks; there’s a 20% chance to use this skill on every defence',
            'catchphrase' => '"We are made stronger by those we fight for."',
            'chance' => 20,
        ],
    ];

    /**
     * Attack the enemy - basically calculate the damage, based on own parameters, special skills etc. and return it to the game engine as an array
     * (one attack can be comprised of many hits thanks to some offensive skills)
     * @return array
     */
    public function attack();

    /**
     * Defend against an attack - basically re-calculate the damage done, based on own parameters, special skills etc.
     * subtract the damage from own HP and return the expanded array to the game engine
     * (one attack can be comprised of many hits, each hit can be separately dodged, or softened by defence or special skills)
     * @param array $hitList the array of hits performed by the attacker
     * @return array
     */
    public function defend(array $hitList);

    /**
     * Dodge an attack and get no damage - based on own Luck - return true or false depending on whether the move was successful
     * An attacker can miss their hit and do no damage if the defender gets lucky that turn.
     * @return bool
     */
    public function dodge();

    /**
     * Get Character current health
     * @return int
     */
    public function getHealth();

    /**
     * Get Character strength property
     * @return int
     */
    public function getStrength();

    /**
     * Get Character strength property
     * @return int
     */
    public function getDefence();

    /**
     * Get Character strength property
     * @return int
     */
    public function getSpeed();

    /**
     * Get Character strength property
     * @return int
     */
    public function getLuck();

    /**
     * Lower Character health parameter
     * @param int $damage Specify the amount of damage dealt to the Character
     * @return self
     */
    public function inflictWound(int $damage);

    /**
     * Increase Character health parameter
     * @param int $value Specify the amount of health points healed
     * @return self
     */
    public function heal(int $value);

    /**
     * Alter Character strength property
     * @param int $value
     * @param string|null $method (default method 'absolute', only other supported alternative is 'relative')
     * @return self
     */
    public function alterStrength(int $value, ?string $method = 'absolute');

    /**
     * Alter Character strength property
     * @param int $value
     * @param string|null $method (default method 'absolute', only other supported alternative is 'relative')
     * @return self
     */
    public function alterDefence(int $value, ?string $method = 'absolute');

    /**
     * Alter Character strength property
     * @param int $value
     * @param string|null $method (default method 'absolute', only other supported alternative is 'relative')
     * @return self
     */
    public function alterSpeed(int $value, ?string $method = 'absolute');

    /**
     * Alter Character strength property
     * @param int $value
     * @param string|null $method (default method 'absolute', only other supported alternative is 'relative')
     * @return self
     */
    public function alterLuck(int $value, ?string $method = 'absolute');
}
