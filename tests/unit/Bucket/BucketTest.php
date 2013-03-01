<?php

use \Bucket\Bucket;

class BucketTest extends PHPUnit_Framework_TestCase
{

    protected $data = array();
    protected $hooks = array();

    protected function setUp()
    {
        $this->data =
            array(
                "index1" => "value1",
                "index2" => "value2",
                1        => "value11",
                2        => "value21",
            );

        $this->hooks["castToInt"] = function ($data) {
            if (!is_scalar($data)) throw new \InvalidArgumentException();

            return (int)$data;
        };

        $this->hooks["castToString"] = function ($data) {
            return (string)$data;
        };
    }

    public function testArrayAccess()
    {
        $bucket = new Bucket();

        $bucket["param1"] = "value1";
        $bucket["param2"] = "value2";

        $this->assertEquals("value1", $bucket["param1"]);
        $this->assertEquals("value2", $bucket["param2"]);

        $this->assertTrue(isset($bucket["param1"]));
        $this->assertFalse(isset($bucket["param3"]));

        unset($bucket["param1"]);
        $this->assertFalse(isset($bucket["param1"]));
    }

    public function testSerialize()
    {
        $bucket = new Bucket();
        $data   = array("param1" => "value1", "param2" => "value2");

        /** @noinspection PhpUndefinedFieldInspection */
        $bucket->param1 = "value1";
        /** @noinspection PhpUndefinedFieldInspection */
        $bucket->param2 = "value2";

        $serialize = serialize($bucket);

        $this->assertEquals(unserialize($serialize), $bucket);
        $this->assertFalse($bucket->unserialize("")); // coverage OCD
    }

    public function testHooks()
    {
        $bucket = new Bucket();

        $bucket->attachHookOnGet(array("param1", "param2"), $this->hooks["castToInt"]);
        $bucket->attachHookOnSet(array("param1", "param2"), $this->hooks["castToString"]);

        /** @noinspection PhpUndefinedFieldInspection */
        $bucket->param1 = false;
        /** @noinspection PhpUndefinedFieldInspection */
        $bucket->param2 = 2.2;

        $this->assertInternalType("int", $bucket->param1);
        $this->assertInternalType("int", $bucket->param1);

        $this->assertContains($this->hooks["castToInt"], $bucket->getHooksOnGet("param1"));
        $this->assertContains($this->hooks["castToInt"], $bucket->getHooksOnGet("param2"));
        $this->assertContains($this->hooks["castToString"], $bucket->getHooksOnSet("param1"));
        $this->assertContains($this->hooks["castToString"], $bucket->getHooksOnSet("param2"));

        $data = $bucket->getData(); // OnGet castToInt
        $raw  = $bucket->getRawData(); // OnSet castToString

        $this->assertSame(0, $data["param1"]);
        $this->assertSame(2, $data["param2"]);

        $this->assertSame("", $raw["param1"]);
        $this->assertSame("2.2", $raw["param2"]);

        $bucket->detachHook(array("param1", "param2"), Bucket::HOOK_DIRECTION_GET);

        $data = $bucket->getData();
        $this->assertSame("", $data["param1"]);
        $this->assertSame("2.2", $data["param2"]);

        $bucket->detachHook(array("param1", "param2"));

        /** @noinspection PhpUndefinedFieldInspection */
        $bucket->param1 = false;
        /** @noinspection PhpUndefinedFieldInspection */
        $bucket->param2 = 2.2;

        $this->assertSame(false, $bucket["param1"]);
        $this->assertSame(2.2, $bucket["param2"]);

        $bucket->detachHook(array("param1", "param2"), "junk");
    }

}
