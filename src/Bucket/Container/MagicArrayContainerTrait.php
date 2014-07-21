<?php

namespace Bucket\Container;

trait MagicArrayContainerTrait
{
    use ArrayContainerTrait;

    /**
     * @param $index
     * @param $value
     */
    public function __set($index, $value)
    {
        $this->offsetSet($index, $value);
    }

    /**
     * @param $index
     * @return null
     */
    public function __get($index)
    {
        return $this->offsetGet($index);
    }

    /**
     * @param $index
     * @return bool
     */
    public function __isset($index)
    {
        return $this->offsetExists($index);
    }

    /**
     * @param $index
     */
    public function __unset($index)
    {
        $this->offsetUnset($index);
    }

}