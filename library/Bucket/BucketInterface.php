<?php

namespace Bucket;

use Bucket\Container\MagicArrayContainerInterface;

interface BucketInterface extends MagicArrayContainerInterface
{

    const HOOK_DIRECTION_GET = "get";
    const HOOK_DIRECTION_SET = "set";

    public function getRawData();

    public function attachHook($index, $hook, $direction);

    public function attachHookOnSet($index, $hook);

    public function attachHookOnGet($index, $hook);

    public function detachHook($index, $direction = null);

    public function getHooks($index, $direction = null);

    public function getHooksOnSet($index);

    public function getHooksOnGet($index);

}
