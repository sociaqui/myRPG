<?php


abstract class AbstractGameCharacter implements GameCharacterInterface
{
    const MIN_HEALTH = 0;
    const MAX_HEALTH = 0;
    const MIN_STRENGTH = 0;
    const MAX_STRENGTH = 0;
    const MIN_DEFENCE = 0;
    const MAX_DEFENCE = 0;
    const MIN_SPEED = 0;
    const MAX_SPEED = 0;
    const MIN_LUCK = 0;
    const MAX_LUCK = 0;

    const OFFENSIVE_ABILITIES = [];
    const DEFENSIVE_ABILITIES = [];

    protected $health;
    protected $strength;
    protected $defence;
    protected $speed;
    protected $luck;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Reset all the parameters to random values within initial ranges.
     * Used at beginning of battles
     */
    public function reset()
    {
        $this->health = rand(static::MIN_HEALTH,static::MAX_HEALTH);
        $this->strength = rand(static::MIN_STRENGTH,static::MAX_STRENGTH);
        $this->defence = rand(static::MIN_DEFENCE,static::MAX_DEFENCE);
        $this->speed = rand(static::MIN_SPEED,static::MAX_SPEED);
        $this->luck = rand(static::MIN_LUCK,static::MAX_LUCK);
    }

    /**
     * @inheritDoc
     */
    public function getHealth()
    {
        // TODO: Implement getHealth() method.
    }

    /**
     * @inheritDoc
     */
    public function inflictWound(int $damage)
    {
        // TODO: Implement inflictWound() method.
    }

    /**
     * @inheritDoc
     */
    public function getStrength()
    {
        // TODO: Implement getStrength() method.
    }

    /**
     * @inheritDoc
     */
    public function getDefence()
    {
        // TODO: Implement getDefence() method.
    }

    /**
     * @inheritDoc
     */
    public function getSpeed()
    {
        // TODO: Implement getSpeed() method.
    }

    /**
     * @inheritDoc
     */
    public function getLuck()
    {
        // TODO: Implement getLuck() method.
    }

    /**
     * @inheritDoc
     */
    public function getOffensiveAbilities()
    {
        // TODO: Implement getOffensiveAbilities() method.
    }

    /**
     * @inheritDoc
     */
    public function getDefensiveAbilities()
    {
        // TODO: Implement getDefensiveAbilities() method.
    }
}
