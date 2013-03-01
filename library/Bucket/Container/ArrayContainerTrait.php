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
        $index = $this->getNormalizedIndex($index);

        $this->data[$index] = $value;
    }

    /**
     * @param $index
     *
     * @return null
     */
    public function offsetGet($index)
    {
        $index = $this->getNormalizedIndex($index);

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
        $index = $this->getNormalizedIndex($index);

        return isset($this->data[$index]);
    }

    /**
     * @param $index
     */
    public function offsetUnset($index)
    {
        $index = $this->getNormalizedIndex($index);

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
