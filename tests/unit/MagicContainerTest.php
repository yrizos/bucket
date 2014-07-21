<?php

use Bucket\Container\MagicContainer;

class MagicContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testInit()
    {
        $array     = ["k1" => "v1", "k2" => "v2", "k3" => "v3"];
        $container = new MagicContainer();

        foreach ($array as $k => $v) $container->$k = $v;

        foreach ($container as $k => $v) {
            $this->assertEquals($array[$k], $v);
        }

        return [$array, $container];
    }

    /**
     * @depends testInit
     */
    public function testDoesntExist($stack)
    {
        list($array, $container) = $stack;

        $this->assertNull($container->doesntExist);
    }

    /**
     * @depends testInit
     */
    public function testIssetUnset($stack)
    {
        list($array, $container) = $stack;

        unset($container->k2);

        $this->assertTrue(isset($container->k1));
        $this->assertFalse(isset($container->k2));
        $this->assertTrue(isset($container->k3));
    }
} 