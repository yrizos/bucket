<?php
namespace Bucket\Container;

class MagicArrayContainer implements MagicArrayContainerInterface
{

    use ArrayContainerTrait;

    public function __set($index, $value)
    {
        $this->offsetSet($index, $value);
    }

    public function __get($index)
    {
        return
            $this->offsetExists($index)
                ? $this->offsetGet($index)
                : null;
    }

    public function __isset($index)
    {
        return $this->offsetExists($index);
    }

    public function __unset($index)
    {
        $this->offsetUnset($index);
    }

}
