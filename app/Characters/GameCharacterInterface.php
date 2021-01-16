<?php


interface GameCharacterInterface
{
    const OFFENSIVE_SKILLS = [
        'double' => [
            'name' => 'Rapid Strike',
            'description' => 'Strike twice during a single attack; there’s a 10% chance to use this skill on every attack',
            'catchphrase' => '"If at first you don\'t succeed, hit them again."',
            'chance' => 10,
        ]
    ];
    const DEFENSIVE_SKILLS = [
        'half' => [
            'name' => 'Magic shield',
            'description' => 'Take only half of the usual damage when an enemy attacks; there’s a 20% change to use this skill on every defence',
            'catchphrase' => '"We are made stronger by those we fight for."',
            'chance' => 20,
        ]
    ];

    /**
     * Get Character current health
     * @return int
     */
    public function getHealth();

    /**
     * Lower Character health parameter
     * @param $damage int Specify the amount of damage dealt to the Character
     * @return int Return the amount of health remaining after the attack
     */
    public function inflictWound(int $damage);

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
     * Get Character Offensive Abilities
     * @return array Empty if none present
     */
    public function getOffensiveAbilities();

    /**
     * Get Character Defensive Abilities
     * @return array Empty if none present
     */
    public function getDefensiveAbilities();
}
