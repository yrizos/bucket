<?php

use \Bucket\Bucket;

class BucketTest extends PHPUnit_Framework_TestCase
{

    protected $bucket;
    protected $data = array();
    protected $hooks = array();

    protected function setUp()
    {
        $this->bucket = new Bucket();
        $this->data =
            array(
                "index1" => "value1",
                "index2" => "value2",
                1 => "value11",
                2 => "value21",
            );

        $this->hooks["castToInt"] = function ($data) {
            if (!is_scalar($data)) throw new \InvalidArgumentException();
            return (int)$data;
        };

        $this->hooks["castToString"] = function ($data) {
            return (string)$data;
        };

    }

    public function testEmpty()
    {
        $this->assertEmpty($this->bucket->getData());
    }

    public function testArrayAccess()
    {
        $this->bucket["index"] = "value";

        $this->assertEquals($this->bucket["index"], "value");
        $this->assertTrue(isset($this->bucket["index"]));
        $this->assertFalse(isset($this->bucket["index1"]));

        unset($this->bucket["index"]);
        $this->assertFalse(isset($this->bucket["index"]));
    }

    public function testSetData()
    {
        $this->bucket->setData($this->data);

        $this->assertEquals(count($this->data), count($this->bucket));

        foreach ($this->data as $key => $value) {
            $this->assertTrue(isset($this->bucket[$key]));
            $this->assertEquals($this->bucket[$key], $value);
        }
    }

    public function testGetData()
    {
        $this->bucket->setData($this->data);

        $this->assertEquals($this->data, $this->bucket->getData());
    }

    public function testGetKeys()
    {
        $this->bucket->setData($this->data);

        $this->assertEquals(array_keys($this->data), $this->bucket->getKeys());
    }

    public function testIterator()
    {
        $this->bucket->setData($this->data);

        foreach ($this->bucket as $key => $value) {
            $this->assertEquals($value, $this->data[$key]);
        }
    }

    public function testTrimOnSet()
    {
        $this->bucket[" index "] = "value";

        $this->assertTrue(isset($this->bucket["index"]));
        $this->assertEquals($this->bucket["index"], "value");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNullIndex()
    {
        $this->bucket[] = "value";
    }

    public function testHooks_1()
    {
        $data =
            array(
                "a" => (float)1.0,
                "b" => (float)2.0,
                "c" => (float)3.0,
                "d" => (float)4.0
            );

        $offsets = array_keys($data);

        $this->bucket->attachHookOnSet($offsets, $this->hooks["castToString"]);
        $this->bucket->attachHookOnGet($offsets, $this->hooks["castToInt"]);
        $this->bucket->setData($data);

        $setValues = $this->bucket->getData(Bucket::HOOK_DIRECTION_SET);
        $getValues = $this->bucket->getData(Bucket::HOOK_DIRECTION_GET);

        $this->assertInternalType("array", $setValues);
        $this->assertEquals($offsets, array_keys($setValues));

        $this->assertInternalType("array", $getValues);
        $this->assertEquals($offsets, array_keys($getValues));

        $this->assertInternalType("int", $getValues["a"]);
        $this->assertInternalType("int", $getValues["b"]);
        $this->assertInternalType("int", $getValues["c"]);
        $this->assertInternalType("int", $getValues["d"]);

        $this->assertInternalType("string", $setValues["a"]);
        $this->assertInternalType("string", $setValues["b"]);
        $this->assertInternalType("string", $setValues["c"]);
        $this->assertInternalType("string", $setValues["d"]);
    }

}
