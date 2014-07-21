<?php

namespace Bucket\Magic;

trait MagicTrait
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param mixed $index
     * @param mixed $value
     */
    public function __set($index, $value)
    {
        $this->data[$index] = $value;
    }

    /**
     * @param mixed $index
     * @return mixed|null
     */
    public function __get($index)
    {
        return
            isset($this->data[$index])
                ? $this->data[$index]
                : null;
    }

    /**
     * @param mixed $index
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