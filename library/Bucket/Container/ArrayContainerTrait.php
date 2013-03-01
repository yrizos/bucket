<?php

namespace Bucket\Container;

trait ArrayContainerTrait
{

    use ContainerTrait;

    /**
     * @param $index
     * @param $value
     */
    public function offsetSet($index, $value)
    {
        $this->data[$index] = $value;
    }

    /**
     * @param $index
     *
     * @return null
     */
    public function offsetGet($index)
    {
        return
            $this->offsetExists($index)
                ? $this->data[$index]
                : null;
    }

    /**
     * @param $index
     *
     * @return bool
     */
    public function offsetExists($index)
    {
        return isset($this->data[$index]);
    }

    /**
     * @param $index
     */
    public function offsetUnset($index)
    {
        if ($this->offsetExists($index)) unset($this->data[$index]);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
