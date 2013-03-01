<?php

namespace Bucket\Container;

trait ContainerTrait
{

    protected $data = array();

    /**
     * @param array|ContainerInterface|\Traversable $data
     * @param bool                                  $reset
     *
     * @return ContainerTrait
     * @throws \InvalidArgumentException
     */
    public function setData($data, $reset = false)
    {
        if ($data instanceof ContainerInterface) $data = $data->getData();
        if (!(is_array($data) || ($data instanceof \Traversable))) throw new \InvalidArgumentException("Data must be traversable.");

        if ($reset === true) $this->data = array();

        return $this->addData($data);
    }

    /**
     * @param array|ContainerInterface|\Traversable $data
     *
     * @return ContainerTrait
     */
    protected function addData($data)
    {
        foreach ($data as $index => $value) {
            $index              = $this->getNormalizedIndex($index);
            $this->data[$index] = $value;
        }

        return $this;
    }

    /**
     * @param $index
     *
     * @return string
     */
    protected function getNormalizedIndex($index)
    {
        if (is_string($index)) $index = trim($index);

        return $index;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
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
        return serialize($this->getData());
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function unserialize($data)
    {
        $data = @unserialize($data);

        if (is_array($data)) {
            $this->setData($data);

            return true;
        }

        return false;
    }

}
