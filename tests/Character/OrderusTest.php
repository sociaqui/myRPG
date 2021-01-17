<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Character\GameCharacterInterface;
use Character\Orderus;

final class OrderusTest extends TestCase
{
    public function testInitialization()
    {
        $ourHero = new Orderus();

        // check if Health is within normal limits
        $this->assertGreaterThanOrEqual($ourHero->getHealth(), Orderus::MAX_HEALTH);
        $this->assertGreaterThanOrEqual(Orderus::MIN_HEALTH, $ourHero->getHealth());

        // check if Strength is within normal limits
        $this->assertGreaterThanOrEqual($ourHero->getStrength(), Orderus::MAX_STRENGTH);
        $this->assertGreaterThanOrEqual(Orderus::MIN_STRENGTH, $ourHero->getStrength());

        // check if Defence is within normal limits
        $this->assertGreaterThanOrEqual($ourHero->getDefence(), Orderus::MAX_DEFENCE);
        $this->assertGreaterThanOrEqual(Orderus::MIN_DEFENCE, $ourHero->getDefence());

        // check if Speed is within normal limits
        $this->assertGreaterThanOrEqual($ourHero->getSpeed(), Orderus::MAX_SPEED);
        $this->assertGreaterThanOrEqual(Orderus::MIN_SPEED, $ourHero->getSpeed());

        // check if Luck is within normal limits
        $this->assertGreaterThanOrEqual($ourHero->getLuck(), Orderus::MAX_LUCK);
        $this->assertGreaterThanOrEqual(Orderus::MIN_LUCK, $ourHero->getLuck());
    }

    public function testHeAttacks()
    {
        $ourHero = new Orderus();

        // get attack hit list
        $hitList = $ourHero->attack();

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

            // check if inside there is a damage value and it is a positive integer greater or equl to Strength property
            //TODO: account for debilitating debuffs or flurry like skills (attack x times but with less strength) in the future
            $this->assertIsInt($hit['damage']);
            $this->assertGreaterThanOrEqual($ourHero->getStrength(), $hit['damage']);

            foreach ($hit['messages'] as $message) {
                $this->assertIsString($message);
                $this->assertNotEmpty($message);
            }
        }
    }

    /**
     * @dataProvider defenceDataProvider
     *
     * @param Orderus $ourHero
     * @param string|null $trait
     */
    public function testHeDefends(Orderus $ourHero, ?string $trait)
    {
        // generate an attack hit list
        $origHitList = $ourHero->attack();

        // sort of attack yourself (for the glory of science)
        $hitList = $ourHero->defend($origHitList);

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
            $maxDamage = ($ourHero->getStrength() - $ourHero->getDefence() < 0) ? 0 : ($ourHero->getStrength() - $ourHero->getDefence());
            if ($trait === 'lucky'){
                // if our Hero is super lucky, every attack will be dodged and the damage should be always 0
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
                    // an unlucky Hero should never be able to dodge
                    $this->assertNotEquals($trait, 'unlucky');
                }

                // check if a Magic shield message properly corresponds to a halved damage value
                //TODO: account for all kinds of strenghtening or debilitating buffs/debuffs or flurry like skills
                // (attack x times but with less strength) in the future
                if ($message === GameCharacterInterface::DEFENSIVE_SKILLS['halfDamage']['catchphrase']) {
                    $this->assertEquals($hit['damage'], $ourHero->getStrength()/2);
                }
            }
        }
    }

    public function testHeBleeds()
    {
        $ourHero = new Orderus();

        // get HP before the wound
        $ourHeroHpBefore = $ourHero->getHealth();

        // inflict a wound
        $ourHero->inflictWound(50);

        // get HP after the wound
        $ourHeroHpAfter = $ourHero->getHealth();

        // check if the damage was properly subtracted from the HP
        $this->assertEquals($ourHeroHpAfter, $ourHeroHpBefore - 50);
    }

    public function defenceDataProvider()
    {
        $randomHero = new Orderus();

        $extraLuckyHero = new Orderus();
        $extraLuckyHero->alterLuck(100); // with Luck over 100% every attack should be dodged

        $completelyUnluckyHero = new Orderus();
        $completelyUnluckyHero->alterLuck(-200); // with Luck under 0% no attack should be dodged

        // datasets duplicated as we are testing semi-random outcomes
        return [
            [$randomHero, null], // a typical hero, no extra traits
            [$randomHero, null], // a typical hero, no extra traits
            [$extraLuckyHero, 'lucky'], // a super lucky hero will dodge every attack
            [$extraLuckyHero, 'lucky'], // a super lucky hero will dodge every attack
            [$extraLuckyHero, 'lucky'], // a super lucky hero will dodge every attack
            [$completelyUnluckyHero, 'unlucky'], // a very unlucky hero has no chance to dodge
            [$completelyUnluckyHero, 'unlucky'], // a very unlucky hero has no chance to dodge
            [$completelyUnluckyHero, 'unlucky'], // a very unlucky hero has no chance to dodge
        ];
    }
}
