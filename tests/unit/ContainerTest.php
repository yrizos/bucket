<?php

use Bucket\Container\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testInit()
    {
        $array = [1, 2, 3];

        $container1 = new Container();
        $container1->setContainer($array);

        $container2 = new Container();
        $container2->setContainer($container1);

        $this->assertEquals($array, $container1->getContainer());
        $this->assertEquals($array, $container2->getContainer());

        return [$container1, $array];
    }

    /**
     * @depends testInit
     */
    public function testTraversable($stack)
    {
        list($container, $array) = $stack;

        foreach ($container as $key => $value) {
            $this->assertEquals($array[$key], $value);
        }
    }

    /**
     * @depends testInit
     */
    public function testCountable($stack)
    {
        list($container, $array) = $stack;

        $this->assertEquals(count($array), count($container));
    }

} 