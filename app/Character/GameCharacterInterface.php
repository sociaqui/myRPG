<?php

namespace Character;


interface GameCharacterInterface
{
    const OFFENSIVE_SKILLS = [
        'doubleHit' => [
            'name' => 'Rapid Strike',
            'description' => 'Strike twice during a single attack; there\'s a 10% chance to use this skill on every attack',
            'catchphrases' => [
                '"If at first you don\'t succeed, hit them again."',
                '"Diplomacy has solved nothing. Only bloodspill can end this now."',
                '"Make every blow count."',
            ],
            'chance' => 10,
        ],
    ];
    const DEFENSIVE_SKILLS = [
        'dodge' => [
            'name' => 'dodge',
            'description' => 'Every character has this basic skill; take no damage from an enemy attack;
            there’s a chance based on luck to use this skill on every defence',
            'catchphrases' => [
                '"Now you see me, now you don\'t."',
                '"Catch me if you can."',
                '"Like a ninja!"',
            ],
            'chance' => 'luck_based',
        ],
        'halfDamage' => [
            'name' => 'Magic shield',
            'description' => 'Take only half of the usual damage when an enemy attacks; there’s a 20% chance to use this skill on every defence',
            'catchphrases' => [
                '"We are made stronger by those we fight for."',
                '"My faith is my shield."',
                '"The stalwart shield, the towering sentinel."',
            ],
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
     * Create a single hit - an array consisting of one message chosen at random from a standard collection and an integer value
     * representing damage done - equal to Character Strength property unless modified by offensive skills
     * @return array
     */
    public function singleHit();

    /**
     * Defend against an attack - basically re-calculate the damage done, based on own parameters, special skills etc.
     * subtract the damage from own HP and return the expanded array to the game engine
     * (one attack can be comprised of many hits, each hit can be separately dodged, or softened by defence or special skills)
     * @param array $hitList the array of hits performed by the attacker
     * @return array
     */
    public function defend(array $hitList);

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
     * Get Character strength property
     * @return bool
     */
    public function getInitiative();

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

    /**
     * Set Character initiative property to true (has gained initiative; is about to attack next turn)
     * @return self
     */
    public function isAboutToAttack();

    /**
     * Set Character initiative property to false (has lost initiative; is not about to attack next turn)
     * @return self
     */
    public function isNotAboutToAttack();
}
