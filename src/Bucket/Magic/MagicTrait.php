<?php

namespace Bucket\Magic;

interface MagicInterface {

    public function __set($index, $value);

    public function __get($index);

    public function __isset($index);

    public function __unset($index);

} 