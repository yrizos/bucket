<?php

use Bucket\Container\ArrayContainer;

class ArrayContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testInit()
    {
        $array     = ["k1" => "v1", "k2" => "v2", "k3" => "v3"];
        $container = new ArrayContainer();

        foreach ($array as $k => $v) $container[$k] = $v;

        foreach ($container as $k => $v) {
            $this->assertEquals($array[$k], $v);
        }

        return [$array, $container];
    }

    public function testNullKeys()
    {
        $array     = ["v1", "v2", "v3"];
        $container = new ArrayContainer();

        foreach ($array as $v) $container[] = $v;

        foreach ($container as $k => $v) {
            $this->assertEquals($array[$k], $v);
        }
    }

    /**
     * @depends testInit
     */
    public function testDoesntExist($stack)
    {
        list($array, $container) = $stack;

        $this->assertNull($container["doesn't exist"]);
    }

    /**
     * @depends testInit
     */
    public function testIssetUnset($stack)
    {
        list($array, $container) = $stack;

        unset($container["k2"]);

        $this->assertTrue(isset($container["k1"]));
        $this->assertFalse(isset($container["k2"]));
        $this->assertTrue(isset($container["k3"]));
    }


}