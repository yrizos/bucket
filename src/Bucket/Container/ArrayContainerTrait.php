<?php

namespace Bucket\Container;

trait ArrayContainerTrait
{
    use ContainerTrait;

    public function offsetSet($index, $value)
    {
        if (is_null($index)) {
            $this->data[] = $value;
        } else {
            $this->data[$index] = $value;
        }
    }

    public function offsetGet($index)
    {
        return
            isset($this->data[$index])
                ? $this->data[$index]
                : null;
    }


    public function offsetExists($index)
    {
        return isset($this->data[$index]);
    }

    public function offsetUnset($index)
    {
        unset($this->data[$index]);
    }
}