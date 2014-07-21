<?php

require "../vendor/autoload.php";

$bucket = new Bucket\Bucket();

$bucket->onGet("name", function ($value) {
    return trim($value);
});

$bucket->onSet("email", function ($value) {

    if (!filter_var($value, FILTER_VALIDATE_EMAIL))
        throw new  \InvalidArgumentException();

    return filter_var($value, FILTER_SANITIZE_EMAIL);
});


$bucket->name = "    Yannis    ";
var_dump($bucket->name); // string 'Yannis' (length=6)

$bucket->email = 42; // will fail


