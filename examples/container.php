<?php

require "../vendor/autoload.php";

class MyContainer implements Bucket\Container\MagicArrayContainerInterface
{
    use Bucket\Container\MagicArrayContainerTrait;
}

$myContainer        = new MyContainer();
$myContainer->label = "hello world";

echo $myContainer["label"];


