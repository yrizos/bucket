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

        foreach ($container as $k => $value) {
            $this->assertEquals($array[$k], $value);
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
                "k1" => "1.1",
                "k2" => "1.2",
            ];

        $array2 =
            [
                "k2" => "2.2",
                "k3" => "2.3"
            ];

        $container = new Container();
        $container->setData($array1);
        $container->merge($array2);

        $data = $container->getData();

        $this->assertArrayHasKey("k1", $data);
        $this->assertArrayHasKey("k2", $data);
        $this->assertArrayHasKey("k3", $data);
        $this->assertEquals($data["k1"], "1.1");
        $this->assertEquals($data["k2"], "2.2");
        $this->assertEquals($data["k3"], "2.3");
    }

    public function testFilter()
    {
        $array =
            [
                "k1" => "v1",
                "k2" => "",
                "k3" => "v3",
            ];

        $container = new Container();
        $container->setData($array);
        $container->filter(function ($value) {
            return !empty($value);
        });

        unset($array["k2"]);

        $this->assertEquals($array, $container->getData());
    }

    public function testMap()
    {
        $array =
            [
                "k1" => "1",
                "k2" => "hello",
                "k3" => "3",
            ];

        $container = new Container();
        $container->setData($array);
        $container->map(function ($value) {
            return (int) $value;
        });

        $data = $container->getData();

        $this->assertInternalType("int", $data["k1"]);
        $this->assertInternalType("int", $data["k2"]);
        $this->assertInternalType("int", $data["k3"]);
        $this->assertEquals(1, $data["k1"]);
        $this->assertEquals(0, $data["k2"]);
        $this->assertEquals(3, $data["k3"]);

    }

} 