<?php

//simple Class Autoloader
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

use UI\BasicCommandLineUserInterface as UserInterface;
use Engine\ImpartialReferee as GameEngine;
use Character\Orderus as Hero;
use Character\WildBeast as Beast;

UserInterface::multipartMessage(UserInterface::INTRODUCTION_TEXT);

// Looped sequence of battles
do {
    //Initialize Battle
    UserInterface::multipartMessage(UserInterface::NEW_ENCOUNTER_TEXT);

    $hero = new Hero();
    $beast = new Beast();

    //Decide initiative
    GameEngine::whoGoesFirst($hero, $beast);
    if ($hero->getInitiative()) {
        UserInterface::message($hero::NAME . ' gets initiative and will attack first');
    } else {
        UserInterface::message($beast::NAME . ' gets initiative and will attack first');
    }

    //Single Battle Loop = 20 turns
    UserInterface::multipartMessage(UserInterface::BATTLE_START_TEXT);
    for ($i = 1; $i <= 20; $i++) {
        if ($hero->getInitiative()) {
            UserInterface::message('(' . $i . ') ' . $hero::NAME . '\'s turn');
            $messages = GameEngine::enactTurn($hero,$beast);
        } else {
            UserInterface::message('(' . $i . ') ' . $beast::NAME . '\'s turn');
            $messages = GameEngine::enactTurn($beast,$hero);
        }
        UserInterface::multipartMessage($messages);
    }

    //Win = Increase Kill Streak
    echo PHP_EOL . 'Orderus wins!' . PHP_EOL;
    $continueCondition = UserInterface::confirmation('Would you like to continue?');

    //Loose = Game Over
    echo 'or Orderus has been defeated. :-(' . PHP_EOL;
    $continueCondition = false;

} while ($continueCondition === true);

//Outro
echo PHP_EOL . 'BYE!';
