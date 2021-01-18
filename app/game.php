<?php

// simple Class Autoloader
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

use UI\BasicCommandLineUserInterface as UserInterface;
use Engine\ImpartialReferee as GameEngine;
use Character\Orderus as Hero;
use Character\WildBeast as Beast;

UserInterface::multipartMessage(UserInterface::INTRODUCTION_TEXT);
$killStreak = 0;

// looped sequence of battles
do {
    // initialize Battle
    UserInterface::multipartMessage(UserInterface::NEW_ENCOUNTER_TEXT);

    $hero = new Hero();
    $beast = new Beast();

    // decide initiative
    GameEngine::whoGoesFirst($hero, $beast);
    if ($hero->getInitiative()) {
        UserInterface::message($hero::NAME . ' gets initiative and will attack first');
    } else {
        UserInterface::message($beast::NAME . ' gets initiative and will attack first');
    }

    // single Battle Loop = 20 turns
    UserInterface::multipartMessage(UserInterface::BATTLE_START_TEXT);
    for ($i = 1; $i <= 20; $i++) {
        if ($hero->getInitiative()) {
            UserInterface::message('(' . $i . ') ' . $hero::NAME . '\'s turn');
            $messages = GameEngine::enactTurn($hero, $beast);
        } else {
            UserInterface::message('(' . $i . ') ' . $beast::NAME . '\'s turn');
            $messages = GameEngine::enactTurn($beast, $hero);
        }

        UserInterface::multipartMessage($messages);

        // stop the Battle is any of the participants gets killed
        if ($hero->getHealth() <= 0 || $beast->getHealth() <= 0) {
            break;
        }
    }

    // decide Battle outcome
    $outcome = GameEngine::battleOutcomeDecider($hero, $beast);

    if ($outcome === 'win') {
        $killStreak++;
        UserInterface::multipartMessage(UserInterface::BATTLE_WON_TEXT);
        $continueCondition = UserInterface::confirmation('Would you like to continue?');
    } elseif ($outcome === 'draw') {
        UserInterface::multipartMessage(UserInterface::BATTLE_INCONCLUSIVE_TEXT);
        $continueCondition = UserInterface::confirmation('Would you like to continue?');
    } elseif ($outcome === 'defeat') {
        UserInterface::multipartMessage(UserInterface::GAME_OVER_TEXT);
        $continueCondition = false;
    } else {
        // this is not a proper outcome
        throw new LogicException('Battle ended in unexpected outcome');
    }

} while ($continueCondition === true);

//Outro
//TODO: implement a High Score logic
if ($killStreak) {
    UserInterface::message(UserInterface::KILLSTREAK_CONGRATULATORY_TEXT . $killStreak);
}
UserInterface::multipartMessage(UserInterface::GOODBYE_TEXT);

