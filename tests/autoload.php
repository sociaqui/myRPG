<?php

//simple Class Autoloader
spl_autoload_register(function ($class_name) {
    include 'app' . DIRECTORY_SEPARATOR . $class_name . '.php';
});
