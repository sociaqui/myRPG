<?php

//simple Class Autoloader
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

use UI\BasicCommandLineUserInterface as UserInterface;
use Engine\ImpartialReferee as GameEngine;
use Character\Orderus as Hero;
use Character\WildBeast as Beast;

//Initialize Environment
/**
 * load all the classes, set any environmental variables etc.
 *
 * Some Greeting? or Menu? for the player
 */
UserInterface::intro();

// Looped battle sequence
do {
    //Initialize Battle
    /**
     * initialize the Hero Object
     * initialize a Wild Beast Object
     */
    echo 'Orderus encounters a wild beast!' . PHP_EOL;

    //Decide initiative
    /**
     * The first attack is done by the player with the higher speed.
     * If both players have the same speed, than the attack is carried on by the player with the highest luck.
     */
    echo '[who] goes first' . PHP_EOL;

    //Single Battle Loop - 20 turns
    for ($i = 1; $i <= 20; $i++) {
        echo '(' . $i . ') [who\'s] turn' . PHP_EOL;
    }

    //Win = Increase Kill Streak
    echo  PHP_EOL . 'Orderus wins!' . PHP_EOL;
    $continueCondition = 'get decision from user'; //TODO: implement a confirmation prompt in the UI

    //Loose = Game Over
    echo 'or Orderus has been defeated. :-(' . PHP_EOL;
    $continueCondition = false;

} while ($continueCondition === true);

//Outro
echo PHP_EOL . 'BYE!';
