<?php

namespace Engine;

use Character\AbstractGameCharacter;
use UI\BasicCommandLineUserInterface as UserInterface;

class ImpartialReferee
{
    /**
     * Check if given skill / trait / special event based on luck will take place
     * @param int $luck
     * @return bool
     */
    public static function proc(int $luck)
    {
        return $luck >= rand(0, 100);
    }

    /**
     * Choose who starts the Battle by attacking first (based on speed first, luck second, and a coin toss if both are
     * completely equal)
     * @param AbstractGameCharacter $hero the hero
     * @param AbstractGameCharacter $beast the beast
     */
    public static function whoGoesFirst(AbstractGameCharacter &$hero, AbstractGameCharacter &$beast)
    {
        if ($hero->getSpeed() === $beast->getSpeed()) {
            if ($hero->getLuck() === $beast->getLuck()) {
                if (rand(0, 1)) {
                    $hero->isAboutToAttack();
                } else {
                    $beast->isAboutToAttack();
                }
            } elseif ($hero->getLuck() > $beast->getLuck()) {
                $hero->isAboutToAttack();
            } else {
                $beast->isAboutToAttack();
            }
        } elseif ($hero->getSpeed() > $beast->getSpeed()) {
            $hero->isAboutToAttack();
        } elseif ($hero->getSpeed() < $beast->getSpeed()) {
            $beast->isAboutToAttack();
        }
    }

    /**
     * Enact a single turn of combat
     * @param AbstractGameCharacter $attacker the attacking character
     * @param AbstractGameCharacter $defender the defending character
     * @return array (a detailed list of messages to be displayed by the interface object
     */
    public static function enactTurn(AbstractGameCharacter &$attacker, AbstractGameCharacter &$defender)
    {
        $hitList = $attacker->attack();
        $updatedHitList = $defender->defend($hitList);

        $result = $updatedHitList['introductoryMessages'];

        foreach ($updatedHitList['hits'] as $hit) {
            $defender->inflictWound($hit['damage']);
            foreach ($hit['messages'] as $message) {
                $result[] = $message;
            }
            $result[] = $defender::NAME . ' is left with ' . $defender->getHealth() . ' HP';
        }

        $attacker->isNotAboutToAttack();
        $defender->isAboutToAttack();

        return $result;
    }

    /**
     * Decide the outcome of the Battle based on the participants health
     * (if both are alive assume the 20 turn limit has elapsed and declare a 'draw')
     * If hero is dead I call it a defeat, even if the beast is slain in the process as well.
     * @param AbstractGameCharacter $hero the hero
     * @param AbstractGameCharacter $beast the beast
     * @return string (namely 'win', 'draw' or 'defeat')
     */
    public static function battleOutcomeDecider(AbstractGameCharacter $hero, AbstractGameCharacter $beast)
    {
        if ($hero->getHealth() <= 0) {
            return 'defeat';
        } elseif ($beast->getHealth() <= 0) {
            return 'win';
        } else {
            return 'draw';
        }
    }
}
