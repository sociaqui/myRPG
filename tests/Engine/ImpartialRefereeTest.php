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
     * @dataProvider enactTurnDataProvider
     *
     * @param AbstractGameCharacter $attacker
     * @param AbstractGameCharacter $defender
     * @param string|bool $expected
     */
    public function testEnactTurn(AbstractGameCharacter $attacker, AbstractGameCharacter $defender, $expected)
    {
        // get attacker's and defender's HP before the turn
        $attackerHpBefore = $attacker->getHealth();
        $defenderHpBefore = $defender->getHealth();

        // use the enactTurn function
        $result = ImpartialReferee::enactTurn($attacker, $defender);

        // get attacker's and defender's HP after the turn
        $attackerHpAfter = $attacker->getHealth();
        $defenderHpAfter = $defender->getHealth();

        // assert that he function always returns a boolean value
        $this->assertIsBool($result);

        // assert that the attacker's HP is intact (unless you want to introduce some counterattack / reflect skills in the future
        $this->assertEquals($attackerHpBefore, $attackerHpAfter);

        // assert that the defender's HP did go down (unless you want to introduce some dodge / immunity skills in the future
        $this->assertGreaterThan($defenderHpAfter, $defenderHpBefore);

        if ($defenderHpAfter <= 0) {
            // if the defender' HP dropped to zero or below in this turn, assert that the function returned false (discontinue the combat loop)
            $this->assertEquals($result, false);
        } else {
            // if the defender' HP didn't drop to zero or below in this turn, assert that the function returned true (continue the combat loop)
            $this->assertEquals($result, true);
        }

        if(is_bool($expected)){
            // if the outcome was defined assert that the function returned what you expected
            $this->assertEquals($result, $expected);
        }
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

    public function enactTurnDataProvider()
    {
        $randomHero1 = new Orderus();
        $randomHero2 = new Orderus();
        $randomBeast1 = new WildBeast();
        $randomBeast2 = new WildBeast();
        $randomBeast3 = new WildBeast();

        $criticallyWoundedHero = new Orderus();
        $criticallyWoundedHero->inflictWound(Orderus::MAX_HEALTH); // with no health defeat in 1 turn is certain
        $criticallyWoundedBeast = new WildBeast();
        $criticallyWoundedBeast->inflictWound(WildBeast::MAX_HEALTH); // with no health defeat in 1 turn is certain

        $superPowerfulHero = new Orderus();
        $superPowerfulHero->alterStrength(80); // with Strength over 150 a fully healthy beast should be slain in 1 hit

        $unbeatableBeast = new WildBeast();
        $unbeatableBeast->alterDefence(200); // with Defence over 200 a fully healthy beast cannot be slain in 1 hit
        // (with currently defined offensive skills anyway)

        return [
            [$randomHero1, $randomBeast1, 'uncertain'], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero1, $randomBeast2, 'uncertain'], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero1, $randomBeast3, 'uncertain'], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero2, $randomBeast1, 'uncertain'], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero2, $randomBeast2, 'uncertain'], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero2, $randomBeast3, 'uncertain'], // a typical first turn, in a normal battle, no certain outcome
            [$randomHero1, $randomHero2, 'uncertain'], // hero vs hero? I don't see why not, no certain outcome
            [$randomBeast1, $randomBeast3, 'uncertain'], // beast vs beast? I don't see why not, no certain outcome
            [$randomBeast1, $criticallyWoundedHero, false], // hero is badly wounded, battle will end in 1 hit
            [$randomHero1, $criticallyWoundedBeast, false], // beast is badly wounded, battle will end in 1 hit
            [$superPowerfulHero, $randomBeast1, false], // hero is overpowered, battle will end in 1 hit
            [$randomHero1, $unbeatableBeast, true], // beast is overpowered, battle will not end in this turn
            [$randomHero2, $unbeatableBeast, true], // beast is overpowered, battle will not end in this turn
        ];
    }
}
