#Bucket [![Build Status](https://travis-ci.org/yrizos/bucket.png?branch=master)](https://travis-ci.org/yrizos/bucket)

A set of basic containers, and a more complicated one with onSet and onGet hooks.

Can be useful if you, like me, are bored writing the same magic and/or `\ArrayAccess` methods over and over again.

##Containers

**Container**

Using it directly:

```php
use Bucket\Container\Container;

$container = new Container();
$container->setData(array("param" => "value");
$data = $container->getData();

echo count($container); // 2
```

Pretty useless, right? Still, you can build on it:

```php
use Bucket\Container\ContainerInterface;
use Bucket\Container\ContainerTrait;

class MyContainer implements ContainerInterface {
    use ContainerTrait;
}
```

and you're ready to go. All three containers come with a trait and an interface you can use in a similar manner.

**MagicContainer**

Adds magic methods to `Container`:

```php
use Bucket\Container\MagicContainer;

$container = new MagicContainer();

$container->param1 = "value1";
$container->param2 = "value2";
```

You can build on it with `MagicContainerTrait` and `MagicContainerInterface`.

**ArrayContainer**

`MagicContainer`'s sibling, but instead of magic method it implements `\ArrayAccess`:

```php
use Bucket\Container\ArrayContainer;

$container = new ArrayContainer();

$container["param1"] = "value1";
$container["param2"] = "value2";
```

You can build on it with `ArrayContainerTrait` and `ArrayContainerInterface`.

**MagicArrayContainer**

```php
use Bucket\Container\MagicArrayContainer;

$container = new MagicArrayContainer();

$container["param1"] = "value1";
$container->param2 = "value2";
```

You can build on it with `MagicArrayContainerTrait` and `MagicArrayContainerInterface`.

##Bucket

This is were it gets a bit (just a tiny bit) more complicated. `Bucket` is build upon `MagicArrayContainer` with added OnSet and OnGet callable hooks.

```php
use Bucket\Bucket;

$bucket = new Bucket;

$bucket->attachHookOnGet(
    array("param1", "param2"),
    function($data) {
        if(is_string($data)) return (int) $data;

        return $data;
    }
);

$bucket->attachHookOnSet(
    "param1",
    function($data) {
        if(!is_string($data)) return (string) $data;

        return $data;
    }
);

$bucket->attachHookOnSet(
    "param2",
    function($data) {
        if(!is_string($data)) return (string) $data;

        return $data;
    }
);

$bucket["param1"] = 1.5;
$bucket->param2 = false;

var_dump($bucket->param1); // int 1
var_dump($bucket["param2"]); // int 0

$raw = $bucket->getRawData();

var_dump($raw["param1"]); // string '1.5'
var_dump($raw["param2"]); // string ''
```

That's it.

##Installation

##Requirements
- PHP 5.3+
- [optional] PHPUnit 3.5+
