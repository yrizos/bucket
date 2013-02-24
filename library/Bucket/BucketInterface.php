<?php

namespace Bucket;

interface BucketInterface extends \ArrayAccess, \Countable, \Serializable, \IteratorAggregate
{
    const HOOK_DIRECTION_GET = "get";
    const HOOK_DIRECTION_SET = "set";

    public function setData(array $array);

    public function getData($direction = self::HOOK_DIRECTION_GET);

    public function getKeys();

    public function attachHook($offset, $hook, $direction);

    public function attachHookOnSet($offset, $hook);

    public function attachHookOnGet($offset, $hook);

    public function detachHook($offset, $direction = null);

    public function getHooks($offset, $direction = null);

    public function getHooksOnSet($offset);

    public function getHooksOnGet($offset);
}
