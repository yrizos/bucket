<?php

namespace Bucket\Container;

interface ContainerInterface extends \Countable, \IteratorAggregate
{

    public function setData($data);

    public function getData();

    public function merge($data);

}