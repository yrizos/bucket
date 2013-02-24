<?php

namespace Bucket;

class Bucket implements BucketInterface
{
    static public $HOOK_DIRECTIONS = array(self::HOOK_DIRECTION_GET, self::HOOK_DIRECTION_SET);

    private $data = array();
    private $hooks = array();

    /**
     * @param string|int $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        $offset = $this->getNormalizedOffset($offset);

        return
            self::offsetExists($offset)
                ? $this->getValue($offset, $this->data[$offset], self::HOOK_DIRECTION_GET)
                : null;
    }

    /**
     * @param string|int $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $offset = $this->getNormalizedOffset($offset);

        $this->data[$offset] = $this->getValue($offset, $value, self::HOOK_DIRECTION_SET);
    }

    /**
     * @param string|int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        $offset = $this->getNormalizedOffset($offset);

        return isset($this->data[$offset]);
    }

    /**
     * @param string|int $offset
     */
    public function offsetUnset($offset)
    {
        $offset = $this->getNormalizedOffset($offset);

        unset($this->data[$offset]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $data = array($this->getData(self::HOOK_DIRECTION_SET), $this->hooks);

        return serialize($data);
    }

    /**
     * @param string $data
     * @return bool|mixed
     */
    public function unserialize($data)
    {
        $data = unserialize($data);
        if (!is_array($data) || count($data) != 2) return false;

        $this->data = $data[0];
        $this->hooks = $data[1];

        return true;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * @param array $array
     * @return Bucket
     */
    public function setData(array $array)
    {
        // setting one by one for set hooks to run
        foreach ($array as $key => $value) self::offsetSet($key, $value);

        return $this;
    }

    /**
     * @param string $direction
     * @return array
     */
    public function getData($direction = self::HOOK_DIRECTION_GET)
    {
        $direction = $this->getNormalizedDirection($direction);
        $data = $this->data;
        foreach ($data as $offset => $value) $data[$offset] = $this->getValue($offset, $value, $direction);

        return $data;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->data);
    }

    /**
     * @param string|int $offset
     * @param callable $hook
     * @param string $direction
     * @return Bucket
     */
    public function attachHook($offset, callable $hook, $direction)
    {
        if (is_array($offset)) {
            foreach ($offset as $value) $this->attachHook($value, $hook, $direction);

            return $this;
        }

        $offset = $this->getNormalizedOffset($offset);
        $direction = $this->getNormalizedDirection($direction);

        $this->hooks[$offset][$direction][] = $hook;

        return $this;
    }

    /**
     * @param string|int $offset
     * @param callable $hook
     * @return Bucket
     */
    public function attachHookOnSet($offset, callable $hook)
    {
        return $this->attachHook($offset, $hook, self::HOOK_DIRECTION_SET);
    }

    /**
     * @param string|int $offset
     * @param callable $hook
     * @return Bucket
     */
    public function attachHookOnGet($offset, callable $hook)
    {
        return $this->attachHook($offset, $hook, self::HOOK_DIRECTION_GET);
    }

    /**
     * Fails silently.
     *
     * @param string|int $offset
     * @param null|string $direction
     * @return Bucket
     */
    public function detachHook($offset, $direction = null)
    {
        if (is_array($offset)) {
            foreach ($offset as $value) $this->detachHook($value, $direction);

            return $this;
        }

        try {
            $offset = $this->getNormalizedOffset($offset);
            if (!is_null($direction)) $direction = $this->getNormalizedDirection($direction);
        } catch (\InvalidArgumentException $e) {
            return $this;
        }

        if (isset($this->hooks[$offset])) {
            if (is_null($direction)) {
                unset($this->hooks[$offset]);
            } else {
                unset($this->hooks[$offset][$direction]);
            }
        }

        return $this;
    }

    /**
     * @param string|int $offset
     * @param string|null $direction
     * @return array
     */
    public function getHooks($offset, $direction = null)
    {
        $offset = $this->getNormalizedOffset($offset);

        if (!isset($this->hooks[$offset])) return array();
        if (is_null($direction)) return $this->hooks[$offset];

        $direction = $this->getNormalizedDirection($direction);

        return
            isset($this->hooks[$offset][$direction])
                ? $this->hooks[$offset][$direction]
                : array();
    }

    /**
     * @param string|int $offset
     */
    public function getHooksOnSet($offset)
    {
        return $this->getHooks($offset, self::HOOK_DIRECTION_SET);
    }

    /**
     * @param string|int $offset
     */
    public function getHooksOnGet($offset)
    {
        return $this->getHooks($offset, self::HOOK_DIRECTION_GET);
    }

    /**
     * Runs hooks on value
     *
     * @param string|int $offset
     * @param mixed $value
     * @param string $direction
     * @return mixed
     */
    private function getValue($offset, $value, $direction)
    {
        $offset = $this->getNormalizedOffset($offset);
        $direction = $this->getNormalizedDirection($direction);
        $hooks = $this->getHooks($offset, $direction);

        if (!empty($hooks)) {
            foreach ($hooks as $hook) {
                $temp = $hook($value);
                if (!is_null($temp)) $value = $temp;
            }
        }

        return $value;
    }

    /**
     * @param int|string $offset
     * @return int|string
     * @throws \InvalidArgumentException
     */
    private function getNormalizedOffset($offset)
    {
        if (!(is_string($offset) || is_int($offset))) throw new \InvalidArgumentException("Offset must be a string or an integer.");

        return is_string($offset) ? trim($offset) : (int)$offset;
    }

    /**
     * @param string $direction
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getNormalizedDirection($direction)
    {
        $direction = is_string($direction) ? strtolower(trim($direction)) : "";

        if (!in_array($direction, self::$HOOK_DIRECTIONS)) throw new \InvalidArgumentException("Hook direction can only be one of: " . explode(", ", self::$HOOK_DIRECTIONS) . ".");

        return $direction;
    }

}
