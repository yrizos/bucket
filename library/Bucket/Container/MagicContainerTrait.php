<?php

namespace Bucket\Container;

trait MagicContainerTrait
{

    use ContainerTrait;

    /**
     * @param $index
     * @param $value
     */
    public function __set($index, $value)
    {
        $this->data[$index] = $value;
    }

    /**
     * @param $index
     *
     * @return null
     */
    public function __get($index)
    {
        return
            isset($this->data[$index])
                ? $this->data[$index]
                : null;
    }

    /**
     * @param $index
     *
     * @return bool
     */
    public function __isset($index)
    {
        return isset($this->data[$index]);
    }

    /**
     * @param $index
     */
    public function __unset($index)
    {
        unset($this->data[$index]);
    }

}
