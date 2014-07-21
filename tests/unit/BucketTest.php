<?php

use Bucket\Bucket;

class BucketTest extends \PHPUnit_Framework_TestCase
{

    public function testOnGet()
    {
        $bucket = new Bucket();

        $bucket->onGet("name", function ($value) {
            return trim($value);
        });

        $bucket->onGet("name", function ($value) {
            return ucfirst($value);
        });

        $bucket->name = " yannis ";

        $this->assertEquals("Yannis", $bucket->name);
    }

    public function testOnSet()
    {
        $bucket = new Bucket();

        $bucket->onSet("email", function ($value) {
            if (is_string($value)) $value = trim($value);

            if (!filter_var($value, FILTER_VALIDATE_EMAIL))
                throw new  \InvalidArgumentException();

            return filter_var($value, FILTER_SANITIZE_EMAIL);
        });


        $bucket->email = " yrizos@gmail.com ";

        $this->assertEquals("yrizos@gmail.com", $bucket->email);
    }

} 