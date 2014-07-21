Bucket
======

Bucket is a collection of container objects.

### Basic containers

There are four basic container traits: 

- ContainerTrait: implements \Countable, \IteratorAggregate
- ArrayContainerTrait: adds \ArrayAccess to the mix
- MagicContainerTrait: if you prefer magic getter & setters to \ArrayAccess
- MagicArrayContainerTrait: the name is revealing, isn't it?

You can either attach the traits to your own classes, or use the concrete classes provided. Here's a quick example: 

```php
class MyContainer implements Bucket\Container\MagicArrayContainerInterface
{
    use Bucket\Container\MagicArrayContainerTrait;
}

$myContainer        = new MyContainer();
$myContainer->label = "hello world";

echo $myContainer["label"];
```

### Bucket

Bucket is a bit more interesting, adding hooks to the mix.

```php
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
```


---

[![Build Status](https://travis-ci.org/yrizos/bucket.png?branch=master)](https://travis-ci.org/yrizos/bucket)