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
        $index = $this->getNormalizedIndex($index);

        $this->data[$index] = $value;
    }

    /**
     * @param $index
     *
     * @return null
     */
    public function __get($index)
    {
        $index = $this->getNormalizedIndex($index);

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
        $index = $this->getNormalizedIndex($index);

        return isset($this->data[$index]);
    }

    /**
     * @param $index
     */
    public function __unset($index)
    {
        $index = $this->getNormalizedIndex($index);

        unset($this->data[$index]);
    }

}
