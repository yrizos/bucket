<?php

namespace Bucket;

use Bucket\Container\MagicArrayContainerTrait;

class Bucket implements BucketInterface
{
    use MagicArrayContainerTrait;

    /**
     * @var array
     */
    protected $hooks = [];

    public function offsetGet($index)
    {
        if (!$this->offsetExists($index)) return null;

        return $this->processValue($this->container[$index], $index, BucketInterface::DIRECTION_GET);
    }

    public function offsetSet($index, $value)
    {
        $this->container[$index] = $this->processValue($value, $index, BucketInterface::DIRECTION_SET);
    }

    public function processValue($value, $index, $direction)
    {
        $hooks = $this->getHooks($index, $direction);

        foreach ($hooks as $hook) $value = $hook($value);

        return $value;
    }

    /**
     * @param string $index
     * @param string $direction
     * @param mixed $callback
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addHook($index, $direction, $callback)
    {
        if (!is_callable($callback)) throw new \InvalidArgumentException("Callback must be callable.");

        $name = $this->getHookName($index, $direction);

        if (!isset($this->hooks[$name])) $this->hooks[$name] = [];
        if (!in_array($callback, $this->hooks[$name])) $this->hooks[$name][] = $callback;

        return $this;
    }

    /**
     * @param string $index
     * @param mixed $callback
     * @return $this
     */
    public function onGet($index, $callback)
    {
        return $this->addHook($index, BucketInterface::DIRECTION_GET, $callback);
    }

    /**
     * @param string $index
     * @param mixed $callback
     * @return $this
     */
    public function onSet($index, $callback)
    {
        return $this->addHook($index, BucketInterface::DIRECTION_SET, $callback);
    }


    /**
     * @param $index
     * @param $direction
     * @return array
     */
    public function getHooks($index, $direction)
    {
        $name = $this->getHookName($index, $direction);

        return
            isset($this->hooks[$name])
                ? $this->hooks[$name]
                : [];
    }

    protected function getHookName($hook, $direction)
    {
        $hook      = strtolower(trim($hook));
        $direction = strtolower(trim($direction));

        return $hook . "-" . $direction;
    }

} 