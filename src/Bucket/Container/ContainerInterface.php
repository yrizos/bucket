<?php

namespace Bucket\Container;

interface ContainerInterface extends \Countable, \IteratorAggregate
{

    public function setContainer($container);

    public function getContainer();

}