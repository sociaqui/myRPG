<?php

namespace UI;


/**
 * Class BasicCommandLineUserInterface
 *
 * Uses the command line to communicate with user
 */
class BasicCommandLineUserInterface implements BasicUserInterface
{
    public static function multipartMessage(array $message)
    {
        foreach ($message as $part) {
            self::message($part);
        };
    }

    public static function message(string $message)
    {
        echo($message . PHP_EOL);
    }

    public static function confirmation(string $prompt = null)
    {
        do {
            $line = readline($prompt . '[Y/N]: ');
            if (in_array($line, ['y', 'Y'])) {
                $answer = true;
            } elseif (in_array($line, ['n', 'N'])) {
                $answer = false;
            } else {
                echo $line . ' is not a valid answer' . PHP_EOL;
            }
        } while (!isset($answer));
        return $answer;
    }

    public static function ask(string $question)
    {
        return readline($question);
    }

    public static function getOption(?string $question, array $options)
    {
        do {
            $line = readline($question . ' (your options are: ' . explode('; ', $options) . ')');
            if (in_array($line, $options)) {
                $answer = $line;
            } else {
                echo $line . ' is not a valid answer' . PHP_EOL;
            }
        } while (!isset($answer));
        return $answer;
    }
}
