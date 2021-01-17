<?php

namespace Engine;

use Character\AbstractGameCharacter;

class ImpartialReferee
{
    /**
     * Check if given skill / trait / special event based on luck will take place
     * @param int $luck
     * @return bool
     */
    public static function proc(int $luck)
    {
        return $luck >= rand(0,100);
    }

    /**
     * Enact a single turn of combat
     * @param AbstractGameCharacter $attacker the attacking character
     * @param AbstractGameCharacter $defender the defending character
     * @return bool
     */
    public static function enactTurn(AbstractGameCharacter $attacker, AbstractGameCharacter $defender)
    {
        // TODO: Implement the actual logic - something along the lines:
        // 1. get attacker's str
        // 2. check for attacker's offensive abilities
        // 3. modify attack accordingly
        // 4. get defender's def
        // 5. check for defender's defensive abilities
        // 6. modify attack accordingly
        // 7. calculate final damage
        // 8. inflict wound
        // 9. get defender's HP after attack and if < 0 set as defeated

        $outcome = 'undecided/continue or win/lose/end';

        return $outcome;
    }
}
