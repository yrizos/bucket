<?php

use Bucket\Container\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testInit()
    {
        $array = [1, 2, 3];

        $container1 = new Container();
        $container1->setData($array);

        $container2 = new Container();
        $container2->setData($container1);

        $this->assertEquals($array, $container1->getData());
        $this->assertEquals($array, $container2->getData());

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

    public function testMerge()
    {
        $array1 =
            [
                "key1" => "1.1",
                "key2" => "1.2",
            ];

        $array2 =
            [
                "key2" => "2.2",
                "key3" => "2.3"
            ];

        $container = new Container();
        $container->setData($array1);
        $container->merge($array2);

        $data = $container->getData();

        $this->assertArrayHasKey("key1", $data);
        $this->assertArrayHasKey("key2", $data);
        $this->assertArrayHasKey("key3", $data);
        $this->assertEquals($data["key1"], "1.1");
        $this->assertEquals($data["key2"], "2.2");
        $this->assertEquals($data["key3"], "2.3");
    }

} 