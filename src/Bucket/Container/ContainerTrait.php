<?php

namespace Bucket\Container;

trait ContainerTrait
{
    /**
     * @var array
     */
    protected $container = [];

    /**
     * @param array|ContainerInterface $container
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setContainer($container)
    {
        if ($container instanceof ContainerInterface) $container = $container->getContainer();
        if (!is_array($container)) throw new \InvalidArgumentException();

        $this->container = $container;

        return $this;
    }

    /**
     * @return array
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->container);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getContainer());
    }
}