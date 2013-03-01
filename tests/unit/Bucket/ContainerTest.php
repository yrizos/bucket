<?php

use Bucket\Container\Container;

class ContainerTest extends PHPUnit_Framework_TestCase
{

    public function testSetData_array()
    {
        $container = new Container();
        $data      = array("param1" => "value1", "param2" => "value2");

        $container->setData($data);

        $this->assertEquals($data, $container->getData());
        $this->assertArrayHasKey("param1", $container->getData());
        $this->assertArrayHasKey("param2", $container->getData());
        $this->assertEquals("value1", $container->getData()["param1"]);
        $this->assertEquals("value2", $container->getData()["param2"]);
    }

    public function testSetData_container()
    {
        $container1 = new Container();
        $container2 = new Container();
        $data       = array("param1" => "value1", "param2" => "value2");

        $container2->setData($data);
        $container1->setData($container2);

        $this->assertEquals($data, $container1->getData());
        $this->assertEquals($data, $container2->getData());
        $this->assertArrayHasKey("param1", $container1->getData());
        $this->assertArrayHasKey("param2", $container1->getData());
        $this->assertEquals("value1", $container1->getData()["param1"]);
        $this->assertEquals("value2", $container1->getData()["param2"]);
    }

    public function testSetData_arrayObject()
    {
        $container = new Container();
        $data      = new \ArrayObject(array("param1" => "value1", "param2" => "value2"));

        $container->setData($data);

        $this->assertEquals($data->getArrayCopy(), $container->getData());
        $this->assertArrayHasKey("param1", $container->getData());
        $this->assertArrayHasKey("param2", $container->getData());
        $this->assertEquals("value1", $container->getData()["param1"]);
        $this->assertEquals("value2", $container->getData()["param2"]);
    }

    public function testCount()
    {
        $container = new Container();
        $data      = array("param1" => "value1", "param2" => "value2");

        $container->setData($data);

        $this->assertEquals(count($data), count($container));
    }

    public function testSerialize()
    {
        $container = new Container();
        $data      = array("param1" => "value1", "param2" => "value2");

        $container->setData($data);

        $serialize = serialize($container);

        $this->assertEquals(unserialize($serialize), $container);
        $this->assertFalse($container->unserialize("")); // coverage OCD
    }

}
