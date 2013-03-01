<?php

use Bucket\Container\MagicArrayContainer;

class MagicArrayContainerTest extends PHPUnit_Framework_TestCase
{

    public function testMagicArrayAccess()
    {
        $container = new MagicArrayContainer();

        $container["param1"] = "value1";
        /** @noinspection PhpUndefinedFieldInspection */
        $container->param2 = "value2";

        $this->assertEquals("value1", $container["param1"]);
        $this->assertEquals("value2", $container["param2"]);
        $this->assertEquals("value1", $container->param1);
        $this->assertEquals("value2", $container->param2);

        $this->assertTrue(isset($container["param1"]));
        $this->assertTrue(isset($container->param1));

        unset($container->param1);
        $this->assertFalse(isset($container["param1"]));

        unset($container["param2"]);
        $this->assertFalse(isset($container->param2));
    }

}
