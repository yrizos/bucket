<?php

namespace Bucket\Container;

trait ArrayContainerTrait
{
    use ContainerTrait;

    public function offsetSet($index, $value)
    {
        if (is_null($index)) {
            $this->container[] = $value;
        } else {
            $this->container[$index] = $value;
        }
    }

    public function offsetGet($index)
    {
        return
            isset($this->container[$index])
                ? $this->container[$index]
                : null;
    }


    public function offsetExists($index)
    {
        return isset($this->container[$index]);
    }

    public function offsetUnset($index)
    {
        unset($this->container[$index]);
    }
}