<?php

use Bucket\Container\MagicContainer;

class MagicContainerTest extends PHPUnit_Framework_TestCase
{

    public function testMagic()
    {
        $magic = new MagicContainer();

        /** @noinspection PhpUndefinedFieldInspection */
        $magic->param1 = "value1";
        /** @noinspection PhpUndefinedFieldInspection */
        $magic->param2 = "value2";

        $this->assertEquals("value1", $magic->param1);
        $this->assertEquals("value2", $magic->param2);

        $this->assertTrue(isset($magic->param1));
        $this->assertFalse(isset($magic->param3));

        unset($magic->param1);
        $this->assertFalse(isset($magic->param1));
    }

}
