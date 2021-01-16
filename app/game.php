<?php

//Initialize Environment
/**
 * load all the classes, set any environmental variables etc.
 *
 * Some Greeting? or Menu? for the player
 */
echo 'Hello!' . PHP_EOL . PHP_EOL;

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
