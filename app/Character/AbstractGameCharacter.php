<?php

namespace Character;


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
        $this->health = rand(static::MIN_HEALTH, static::MAX_HEALTH);
        $this->strength = rand(static::MIN_STRENGTH, static::MAX_STRENGTH);
        $this->defence = rand(static::MIN_DEFENCE, static::MAX_DEFENCE);
        $this->speed = rand(static::MIN_SPEED, static::MAX_SPEED);
        $this->luck = rand(static::MIN_LUCK, static::MAX_LUCK);
    }

    /**
     * @inheritDoc
     */
    public function attack()
    {
        // generate a semi-random hit message
        $plainHitMessages = [' hits ', ' slaps ', ' stabs ', ' kicks ', ' punches '];
        $message = explode(DIRECTORY_SEPARATOR,static::class)[1] . $plainHitMessages[rand(0, count($plainHitMessages))];

        // generate a single hit
        $singleHit = [
            'messages' => [$message],
            'damage' => $this->strength
        ];

        // create a basic attack - a single hit array with no introductory messages, damage equal to Strength and a simple message
        $attack = [
            'introductoryMessages' => [],
            'hits' => [$singleHit]
        ];

        // check for offensive abilities and modify the attack accordingly - used foreach + switch for future scalability
        //TODO: mind how skills interact with each other though - a luck buff should be added before calculating the rest
        // of the probabilities, a multihit should not proc on the additional hits it created, a Strength buff should
        // affect all the hits but a critical hit proc should probably be calculated separately for each hit
        foreach (static::OFFENSIVE_ABILITIES as $ability) {
            switch ($ability) {
                case 'doubleHit':
                    $chance = self::OFFENSIVE_SKILLS['doubleHit']['chance'] === 'luck_based' ? $this->luck : self::OFFENSIVE_SKILLS['doubleHit']['chance'];
                    // TODO: Implement this steps carefully. This might be a bit more tricky than I originally anticipated.
                    break;
                case 'criticalHit':
                    // TODO: Implement this logic. Just a placeholder to showcase possibilities for now.
                    break;
            }
        }

        return $attack;
    }

    /**
     * @inheritDoc
     */
    public function defend(array $hitList)
    {
        // TODO: Implement the proper steps
        return $hitList; //for now
    }

    /**
     * @inheritDoc
     */
    public function dodge()
    {
        // TODO: Implement dodge() method.
    }

    /**
     * @inheritDoc
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @inheritDoc
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @inheritDoc
     */
    public function getDefence()
    {
        return $this->defence;
    }

    /**
     * @inheritDoc
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @inheritDoc
     */
    public function getLuck()
    {
        return $this->luck;
    }

    /**
     * @inheritDoc
     */
    public function inflictWound(int $damage)
    {
        $this->health -= $damage;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function heal(int $value)
    {
        $this->health += $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function alterStrength(int $value, ?string $method = 'absolute')
    {
        switch ($method){
            case 'absolute':
                $this->strength += $value;
                break;
            case 'relative':
                $this->strength *= $value;
                break;
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function alterDefence(int $value, ?string $method = 'absolute')
    {
        switch ($method){
            case 'absolute':
                $this->defence += $value;
                break;
            case 'relative':
                $this->defence *= $value;
                break;
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function alterSpeed(int $value, ?string $method = 'absolute')
    {
        switch ($method){
            case 'absolute':
                $this->speed += $value;
                break;
            case 'relative':
                $this->speed *= $value;
                break;
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function alterLuck(int $value, ?string $method = 'absolute')
    {
        switch ($method){
            case 'absolute':
                $this->luck += $value;
                break;
            case 'relative':
                $this->luck *= $value;
                break;
        }
        return $this;
    }
}
