<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Character\AbstractGameCharacter;
use Character\Orderus;
use Character\WildBeast;
use Engine\ImpartialReferee;

final class ImpartialRefereeTest extends TestCase
{
    /**
     * @dataProvider procDataProvider
     *
     * @param int $luck
     * @param array $expected
     */
    public function testProc(int $luck, array $expected)
    {
        // use the proc function
        $result = ImpartialReferee::proc($luck);

        // assert that he function always returns a boolean value
        $this->assertIsBool($result);

        // assert that the function returned what you expected
        $this->assertContains($result, $expected);
    }

    /**
     * @dataProvider whoGoesFirstDataProvider
     *
     * @param AbstractGameCharacter $hero
     * @param AbstractGameCharacter $beast
     * @param string $expected
     */
    public function testWhoGoesFirst(AbstractGameCharacter $hero, AbstractGameCharacter $beast, string $expected)
    {
        // use the whoGoesFirst function
        ImpartialReferee::whoGoesFirst($hero, $beast);

        if ($expected === 'hero') {
            // we were expecting the hero to start the battle
            $this->assertTrue($hero->getInitiative());
            $this->assertFalse($beast->getInitiative());
        } elseif ($expected === 'beast') {
            // we were expecting the beast to start the battle
            $this->assertTrue($beast->getInitiative());
            $this->assertFalse($hero->getInitiative());
        } else {
            // well there is not really a third party here is there?
            $this->fail('Failed to expect a hero or beast to start battle, who were you expecting?');
        }
    }

    /**
     * @dataProvider enactTurnDataProvider
     *
     * @param AbstractGameCharacter $attacker
     * @param AbstractGameCharacter $defender
     */
    public function testEnactTurn(AbstractGameCharacter $attacker, AbstractGameCharacter $defender)
    {
        // get attacker's and defender's HP before the turn
        $attackerHpBefore = $attacker->getHealth();
        $defenderHpBefore = $defender->getHealth();

        // use the enactTurn function
        $result = ImpartialReferee::enactTurn($attacker, $defender);

        // check the result is a list of messages and it is not empty
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        // get attacker's and defender's HP after the turn
        $attackerHpAfter = $attacker->getHealth();
        $defenderHpAfter = $defender->getHealth();

        // assert that the attacker's HP is intact (unless you want to introduce some counterattack / reflect skills in the future)
        $this->assertEquals($attackerHpBefore, $attackerHpAfter);

        // assert that the defender's HP did go down or is at least the same (if attack fully dodged - unless you want to introduce some healing skills in the future)
        $this->assertGreaterThanOrEqual($defenderHpAfter, $defenderHpBefore);

        // assert that the attacker lost initiative
        $this->assertFalse($attacker->getInitiative());

        // assert that the defender gained initiative
        $this->assertTrue($defender->getInitiative());
    }

    /**
     * @dataProvider battleOutcomeDeciderDataProvider
     *
     * @param AbstractGameCharacter $hero
     * @param AbstractGameCharacter $beast
     * @param string $expected
     */
    public function testBattleOutcomeDecider(AbstractGameCharacter $hero, AbstractGameCharacter $beast, string $expected)
    {
        // use the battleOutcomeDecider function
        $result = ImpartialReferee::battleOutcomeDecider($hero, $beast);

        $this->assertEquals($result, $expected);
    }

    public function procDataProvider()
    {
        return [
            [0, [false]], // with no luck, always fail
            [-30, [false]], // game currently supports negative luck (maybe used with some debuffs in the future, always fail
            [100, [true]], // with 100% luck, always succeed
            [150, [true]], // game currently supports luck over 100% (maybe used with some buffs in the future, always succeed
            [75, [true, false]], // with 75% luck, could succeed, could fail
            [50, [true, false]], // with 50% luck, could succeed, could fail
            [25, [true, false]], // with 25% luck, could succeed, could fail
        ];
    }

    public function whoGoesFirstDataProvider()
    {
        $fastHero = new Orderus();
        $fastHero->alterSpeed(50);
        $slowHero = new Orderus();
        $slowHero->alterSpeed(-50);
        $averageSpeedHeroWithLuck = new Orderus();
        $averageSpeedHeroWithoutLuck = clone($averageSpeedHeroWithLuck);
        $averageSpeedHeroWithLuck->alterLuck(50);
        $fastBeast = new WildBeast();
        $fastBeast->alterSpeed(50);
        $slowBeast = new WildBeast();
        $slowBeast->alterSpeed(-50);
        $averageSpeedBeastWithLuck = new WildBeast();
        $averageSpeedBeastWithoutLuck = clone($averageSpeedBeastWithLuck);
        $averageSpeedBeastWithLuck->alterLuck(50);

        return [
            [$fastHero, $slowBeast, 'hero'],
            [$slowHero, $fastBeast, 'beast'],
            [$averageSpeedBeastWithLuck, $averageSpeedBeastWithoutLuck, 'hero'],
            [$averageSpeedHeroWithoutLuck, $averageSpeedHeroWithLuck, 'beast'],
        ];
    }

    public function enactTurnDataProvider()
    {
        $randomHero1 = new Orderus();
        $randomHero2 = new Orderus();
        $randomBeast1 = new WildBeast();
        $randomBeast2 = new WildBeast();

        $criticallyWoundedHero = new Orderus();
        $criticallyWoundedHero->inflictWound(Orderus::MAX_HEALTH); // with no health defeat in 1 turn is certain
        $criticallyWoundedBeast = new WildBeast();
        $criticallyWoundedBeast->inflictWound(WildBeast::MAX_HEALTH); // with no health defeat in 1 turn is certain

        $superPowerfulHero = new Orderus();
        $superPowerfulHero->alterStrength(80); // with Strength over 150 a fully healthy beast should be slain in 1 hit

        $unbeatableBeast = new WildBeast();
        $unbeatableBeast->alterDefence(200); // with Defence over 200 a fully healthy beast cannot be slain in 1 hit
        // (with currently defined offensive skills anyway)

        /**
         * not checking the outcome of the battle in the enactTurn function anymore anyway, but will leave these thest
         * cases here in case they become relevant in the future
         */

        return [
            [$randomHero1, $randomBeast1], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero1, $randomBeast2], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero2, $randomBeast1], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero2, $randomBeast2], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero1, $randomHero2], // hero vs hero? I don't see why not, no certain outcome
            [$randomBeast1, $randomBeast2], // beast vs beast? I don't see why not, no certain outcome
            [$randomBeast1, $criticallyWoundedHero], // hero is badly wounded, battle will end in 1 hit
            [$randomHero1, $criticallyWoundedBeast], // beast is badly wounded, battle will end in 1 hit
            [$superPowerfulHero, $randomBeast1], // hero is overpowered, battle will end in 1 hit
            [$randomHero1, $unbeatableBeast], // beast is overpowered, battle will not end in this turn
            [$randomHero2, $unbeatableBeast], // beast is overpowered, battle will not end in this turn
        ];
    }

    public function battleOutcomeDeciderDataProvider()
    {
        $freshHero = new Orderus();
        $criticallyWoundedHero = new Orderus();
        $criticallyWoundedHero->inflictWound(Orderus::MAX_HEALTH);
        $freshBeast = new WildBeast();
        $criticallyWoundedBeast = new WildBeast();
        $criticallyWoundedBeast->inflictWound(WildBeast::MAX_HEALTH);

        return [
            [$freshHero, $criticallyWoundedBeast, 'win'],
            [$freshHero, $freshBeast, 'draw'],
            [$criticallyWoundedHero, $freshBeast, 'defeat'],
            [$criticallyWoundedHero, $criticallyWoundedHero, 'defeat'], // might be called a draw by some,
            // but if the hero dies I call it a defeat, even if the beast is slain in the process as well.
        ];
    }
}
