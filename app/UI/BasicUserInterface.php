<?php

namespace UI;


/**
 * Interface BasicUserInterface
 *
 * Defines the basic structure of a simple interface
 */
interface BasicUserInterface
{
    const INTRODUCTION_TEXT = 'Hello!';
    const GOODBYE_TEXT = 'Bye!';

    /**
     * Display the introduction / greeting to the user
     * @return null
     */
    public static function intro();

    /**
     * Display the farewell to the user
     * @return null
     */
    public static function outro();

    /**
     * Display a message to the user
     * @param $message string The mesage to be displayed.
     * @return null
     */
    public static function message(string $message);

    /**
     * Get confirmation from the user
     * @param $prompt string | null Specify an (optional) string with which to prompt the user.
     * @return bool
     */
    public static function confirmation(string $prompt = null);

    /**
     * Get an answer to an open choice question from the user
     * @param $question string | null Specify a question with which to prompt the user.
     * @return string
     */
    public static function ask(string $question);

    /**
     * Get an answer to a closed choice question from the user
     * @param $question string | null Specify a question with which to prompt the user.
     * @param $options array Specify the possible options / answers.
     * @return string
     */
    public static function getOption(string $question, array $options);
}
