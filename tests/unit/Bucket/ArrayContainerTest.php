<?php

use Bucket\Container\ArrayContainer;

class ArrayContainerTest extends PHPUnit_Framework_TestCase
{

    public function testArrayAccess()
    {
        $array = new ArrayContainer();

        $array["param1"] = "value1";
        $array["param2"] = "value2";

        $this->assertEquals("value1", $array["param1"]);
        $this->assertEquals("value2", $array["param2"]);

        $this->assertTrue(isset($array["param1"]));
        $this->assertFalse(isset($array["param3"]));

        unset($array["param1"]);
        $this->assertFalse(isset($array["param1"]));
    }

    public function testIterator()
    {
        $array = new ArrayContainer();

        $array["param1"] = "value1";
        $array["param2"] = "value2";
        $array["param3"] = "value2";

        foreach ($array as $key => $value) {
            $this->assertEquals($value, $array[$key]);
        }
    }
}
