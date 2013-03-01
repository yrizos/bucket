<?php
namespace Bucket\Container;

interface ContainerInterface extends \Countable, \Serializable
{

    public function setData($data, $reset = false);

    public function getData();

}
