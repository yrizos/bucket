<?php

namespace Bucket;

use Bucket\Container\MagicArrayContainerInterface;

interface BucketInterface extends MagicArrayContainerInterface
{
    const DIRECTION_GET = "get";
    const DIRECTION_SET = "set";

    public function addHook($index, $direction, $callback);

    public function onGet($index, $callback);

    public function onSet($index, $callback);

} 