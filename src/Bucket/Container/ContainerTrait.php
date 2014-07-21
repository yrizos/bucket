<?php

namespace Bucket\Container;

trait ContainerTrait
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array|ContainerInterface $data
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setData($data)
    {
        if ($data instanceof ContainerInterface) $data = $data->getData();
        if (!is_array($data)) throw new \InvalidArgumentException();

        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array|ContainerInterface $data
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function merge($data)
    {
        if ($data instanceof ContainerInterface) $data = $data->getData();
        if (!is_array($data)) throw new \InvalidArgumentException();

        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * @param $callback
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function filter($callback)
    {
        if (!is_callable($callback)) throw new \InvalidArgumentException();

        $data = array_filter($this->getData(), $callback);

        return $this->setData($data);
    }

    public function map($callback)
    {
        if (!is_callable($callback)) throw new \InvalidArgumentException();

        $data = array_map($callback, $this->getData());

        return $this->setData($data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getData());
    }
}