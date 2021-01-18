<?php

namespace UI;


/**
 * Interface BasicUserInterface
 *
 * Defines the basic structure of a simple interface
 */
interface BasicUserInterface
{
    const INTRODUCTION_TEXT = ['', '', '           * * * eMAG\'s Hero * * *',
        'A small, simplistic RPG game by Jarek Sochacki.', '',
        'Once upon a time there was a great hero, called Orderus,', 'with some strengths and weaknesses, as all heroes have.',
        'After battling all kinds of monsters for more than a hundred years,', 'Orderus is now embarking on a new journey...', ''];
    const NEW_ENCOUNTER_TEXT = ['As Orderus walks the ever-green forests of Emagia, he encounters a wild beast!'];
    const BATTLE_START_TEXT = ['   ~ ~ ~ FIGHT! ~ ~ ~'];
    const GAME_OVER_TEXT = ['Oh no!', 'Orderus has been slain!', 'GAME OVER', ''];
    const GOODBYE_TEXT = ['Goodbye!'];

    /**
     * Display a multi part message to the user
     * @param array $message The multi part message to be displayed.
     * @return null
     */
    public static function multipartMessage(array $message);

    /**
     * Display a message to the user
     * @param string $message The message to be displayed.
     * @return null
     */
    public static function message(string $message);

    /**
     * Get confirmation from the user
     * @param string|null $prompt Specify an (optional) string with which to prompt the user.
     * @return bool
     */
    public static function confirmation(?string $prompt);

    /**
     * Get an answer to an open choice question from the user
     * @param string $question Specify a question with which to prompt the user.
     * @return string
     */
    public static function ask(string $question);

    /**
     * Get an answer to a closed choice question from the user
     * @param string|null $question Specify an (optional) question with which to prompt the user.
     * @param array $options Specify the possible options / answers.
     * @return string
     */
    public static function getOption(?string $question, array $options);
}
