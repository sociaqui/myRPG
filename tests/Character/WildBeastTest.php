<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Character\GameCharacterInterface;
use Character\WildBeast;

final class WildBeastTest extends TestCase
{
    public function testInitialization()
    {
        $ourBeast = new WildBeast();

        // check if Health is within normal limits
        $this->assertGreaterThanOrEqual($ourBeast->getHealth(), WildBeast::MAX_HEALTH);
        $this->assertGreaterThanOrEqual(WildBeast::MIN_HEALTH, $ourBeast->getHealth());

        // check if Strength is within normal limits
        $this->assertGreaterThanOrEqual($ourBeast->getStrength(), WildBeast::MAX_STRENGTH);
        $this->assertGreaterThanOrEqual(WildBeast::MIN_STRENGTH, $ourBeast->getStrength());

        // check if Defence is within normal limits
        $this->assertGreaterThanOrEqual($ourBeast->getDefence(), WildBeast::MAX_DEFENCE);
        $this->assertGreaterThanOrEqual(WildBeast::MIN_DEFENCE, $ourBeast->getDefence());

        // check if Speed is within normal limits
        $this->assertGreaterThanOrEqual($ourBeast->getSpeed(), WildBeast::MAX_SPEED);
        $this->assertGreaterThanOrEqual(WildBeast::MIN_SPEED, $ourBeast->getSpeed());

        // check if Luck is within normal limits
        $this->assertGreaterThanOrEqual($ourBeast->getLuck(), WildBeast::MAX_LUCK);
        $this->assertGreaterThanOrEqual(WildBeast::MIN_LUCK, $ourBeast->getLuck());
    }

    public function testItAttacks()
    {
        $ourBeast = new WildBeast();

        // get attack hit list
        $hitList = $ourBeast->attack();

        // check if you got an array and it is not empty
        $this->assertIsArray($hitList);
        $this->assertNotEmpty($hitList);

        // check if inside there is a list of introductory messages (it might be empty)
        $this->assertIsArray($hitList['introductoryMessages']);

        foreach ($hitList['introductoryMessages'] as $message) {
            $this->assertIsString($message);
            $this->assertNotEmpty($message);
        }

        // check if inside there is a list of hits and it is not empty
        $this->assertIsArray($hitList['hits']);
        $this->assertNotEmpty($hitList['hits']);

        foreach ($hitList['hits'] as $hit) {
            // check if inside there is a list of messages and it is not empty
            $this->assertIsArray($hit['messages']);
            $this->assertNotEmpty($hit['messages']);

            // check if inside there is a damage value and it is a positive integer greater or equal to Strength property
            //TODO: account for debilitating debuffs or flurry like skills (attack x times but with less strength) in the future
            $this->assertIsInt($hit['damage']);
            $this->assertGreaterThanOrEqual($ourBeast->getStrength(), $hit['damage']);

            foreach ($hit['messages'] as $message) {
                $this->assertIsString($message);
                $this->assertNotEmpty($message);
            }
        }
    }

    /**
     * @dataProvider defenceDataProvider
     *
     * @param WildBeast $ourBeast
     * @param string|null $trait
     */
    public function testItDefends(WildBeast $ourBeast, ?string $trait)
    {
        // generate an attack hit list
        $origHitList = $ourBeast->attack();

        // sort of attack yourself (for the glory of science)
        $hitList = $ourBeast->defend($origHitList);

        // check if you got an array and it is not empty
        $this->assertIsArray($hitList);
        $this->assertNotEmpty($hitList);

        // check if inside there is a list of introductory messages (it might be empty)
        $this->assertIsArray($hitList['introductoryMessages']);

        foreach ($hitList['introductoryMessages'] as $message) {
            $this->assertIsString($message);
            $this->assertNotEmpty($message);
        }

        // check if inside there is a list of hits and it is not empty
        $this->assertIsArray($hitList['hits']);
        $this->assertNotEmpty($hitList['hits']);

        foreach ($hitList['hits'] as $hit) {
            // check if inside there is a list of messages and it is not empty
            $this->assertIsArray($hit['messages']);
            $this->assertNotEmpty($hit['messages']);

            // check if inside there is a damage value and it is a positive integer (can be zero)
            // but it must be also at least lower than the initial Strength property minus the Defence property
            // (unless the subtraction gets below zero, than it's still zero)
            $maxDamage = ($ourBeast->getStrength() - $ourBeast->getDefence() < 0) ? 0 : ($ourBeast->getStrength() - $ourBeast->getDefence());
            if ($trait === 'lucky'){
                // if the beast is super lucky, every attack will be dodged and the damage should be always 0
                $maxDamage = 0;
            }
            $this->assertIsInt($hit['damage']);
            $this->assertGreaterThanOrEqual(0, $hit['damage']);
            $this->assertGreaterThanOrEqual($hit['damage'], $maxDamage);

            foreach ($hit['messages'] as $message) {
                $this->assertIsString($message);
                $this->assertNotEmpty($message);

                // check if a dodge message properly corresponds to a zero damage value
                if ($message === GameCharacterInterface::DEFENSIVE_SKILLS['dodge']['catchphrase']) {
                    $this->assertGreaterThanOrEqual($hit['damage'], 0);
                    // an unlucky beast should never be able to dodge
                    $this->assertNotEquals($trait, 'unlucky');
                }
            }
        }
    }

    public function testItBleeds()
    {
        $ourBeast = new WildBeast();

        // get HP before the wound
        $ourBeastHpBefore = $ourBeast->getHealth();

        // inflict a wound
        $ourBeast->inflictWound(50);

        // get HP after the wound
        $ourBeastHpAfter = $ourBeast->getHealth();

        // check if the damage was properly subtracted from the HP
        $this->assertEquals($ourBeastHpAfter, $ourBeastHpBefore + 50);
    }

    public function defenceDataProvider()
    {
        $randomBeast = new WildBeast();

        $extraLuckyBeast = new WildBeast();
        $extraLuckyBeast->alterLuck(100); // with Luck over 100% every attack should be dodged

        $completelyUnluckyBeast = new WildBeast();
        $completelyUnluckyBeast->alterLuck(-200); // with Luck under 0% no attack should be dodged

        // datasets duplicated as we are testing semi-random outcomes
        return [
            [$randomBeast, null], // a typical beast, no extra traits
            [$randomBeast, null], // a typical beast, no extra traits
            [$extraLuckyBeast, 'lucky'], // a super lucky beast will dodge every attack
            [$extraLuckyBeast, 'lucky'], // a super lucky beast will dodge every attack
            [$extraLuckyBeast, 'lucky'], // a super lucky beast will dodge every attack
            [$completelyUnluckyBeast, 'unlucky'], // a very unlucky beast has no chance to dodge
            [$completelyUnluckyBeast, 'unlucky'], // a very unlucky beast has no chance to dodge
            [$completelyUnluckyBeast, 'unlucky'], // a very unlucky beast has no chance to dodge
        ];
    }
}
