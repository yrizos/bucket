<?php

namespace Bucket;

use Bucket\Container\MagicArrayContainer;

class Bucket extends MagicArrayContainer implements BucketInterface
{

    protected $hooks = array();

    /**
     * @return array
     */
    public function getData()
    {
        $data = $this->getRawData();

        foreach ($data as $index => $value) $data[$index] = $this->getValue($index, $value, self::HOOK_DIRECTION_GET);

        return $data;
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return $this->data;
    }

    /**
     * @param mixed $index
     *
     * @return mixed|null
     */
    public function offsetGet($index)
    {
        return
            $this->offsetExists($index)
                ? $this->getValue($index, $this->data[$index], self::HOOK_DIRECTION_GET)
                : null;
    }

    /**
     * @param mixed $index
     * @param mixed $value
     */
    public function offsetSet($index, $value)
    {
        $this->data[$index] = $this->getValue($index, $value, self::HOOK_DIRECTION_SET);
    }

    /**
     * Runs hooks on value
     *
     * @param mixed  $index
     * @param mixed  $value
     * @param string $direction
     *
     * @return mixed
     */
    protected function getValue($index, $value, $direction)
    {
        $direction = $this->getNormalizedDirection($direction);
        $hooks     = $this->getHooks($index, $direction);

        if (!empty($hooks)) {
            foreach ($hooks as $hook) {
                $temp = $hook($value);
                if (!is_null($temp)) $value = $temp;
            }
        }

        return $value;
    }

    /**
     * @param mixed  $index
     * @param        $hook
     * @param string $direction
     *
     * @return Bucket
     */
    public function attachHook($index, $hook, $direction)
    {

        if (!is_callable($hook)) return $this;

        if (is_array($index)) {
            foreach ($index as $value) $this->attachHook($value, $hook, $direction);

            return $this;
        }

        $index     = $this->getNormalizedIndex($index);
        $direction = $this->getNormalizedDirection($direction);

        $this->hooks[$index][$direction][] = $hook;

        return $this;
    }

    /**
     * @param mixed $index
     * @param       $hook
     *
     * @return Bucket
     */
    public function attachHookOnSet($index, $hook)
    {
        return $this->attachHook($index, $hook, self::HOOK_DIRECTION_SET);
    }

    /**
     * @param mixed $index
     * @param       $hook
     *
     * @return Bucket
     */
    public function attachHookOnGet($index, $hook)
    {
        return $this->attachHook($index, $hook, self::HOOK_DIRECTION_GET);
    }

    /**
     * Fails silently.
     *
     * @param mixed       $index
     * @param null|string $direction
     *
     * @return Bucket
     */
    public function detachHook($index, $direction = null)
    {
        if (is_array($index)) {
            foreach ($index as $value) $this->detachHook($value, $direction);

            return $this;
        }

        $index = $this->getNormalizedIndex($index);

        try {
            if (!is_null($direction)) $direction = $this->getNormalizedDirection($direction);
        } catch (\InvalidArgumentException $e) {
            return $this;
        }

        if (isset($this->hooks[$index])) {
            if (is_null($direction)) {
                unset($this->hooks[$index]);
            } else {
                unset($this->hooks[$index][$direction]);
            }
        }

        return $this;
    }

    /**
     * @param mixed       $index
     * @param string|null $direction
     *
     * @return array
     */
    public function getHooks($index, $direction = null)
    {
        $index = $this->getNormalizedIndex($index);

        if (!isset($this->hooks[$index])) return array();
        if (is_null($direction)) return $this->hooks[$index];

        $direction = $this->getNormalizedDirection($direction);

        return
            isset($this->hooks[$index][$direction])
                ? $this->hooks[$index][$direction]
                : array();
    }

    /**
     * @param mixed $index
     *
     * @return array
     */
    public function getHooksOnSet($index)
    {
        return $this->getHooks($index, self::HOOK_DIRECTION_SET);
    }

    /**
     * @param mixed $index
     *
     * @return array
     */
    public function getHooksOnGet($index)
    {
        return $this->getHooks($index, self::HOOK_DIRECTION_GET);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $data = array($this->getData(), $this->hooks);

        return serialize($data);
    }

    /**
     * @param string $data
     *
     * @return bool|mixed
     */
    public function unserialize($data)
    {
        $data = unserialize($data);
        if (!is_array($data) || count($data) != 2) return false;

        $this->setData($data[0]);
        $this->hooks = $data[1];

        return true;
    }

    /**
     * @param string $direction
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getNormalizedDirection($direction)
    {
        $direction = is_string($direction) ? strtolower(trim($direction)) : "";

        if (!in_array($direction, self::getHookDirections())) throw new \InvalidArgumentException("Hook direction can only be one of: " . implode(", ", self::getHookDirections()) . ".");

        return $direction;
    }

    /**
     * @return array
     */
    public static function getHookDirections()
    {
        return array(self::HOOK_DIRECTION_GET, self::HOOK_DIRECTION_SET);
    }
}
