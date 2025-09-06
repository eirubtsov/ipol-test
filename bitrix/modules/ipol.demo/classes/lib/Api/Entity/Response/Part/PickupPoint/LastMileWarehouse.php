<?php


namespace Ipol\Demo\Api\Entity\Response\Part\PickupPoint;

use Ipol\Demo\Api\Entity\AbstractEntity;
use Ipol\Demo\Api\Entity\Response\Part\AbstractResponsePart;

/**
 * Class WorkHours
 * @package Ipol\Demo\Api\Entity\Response\Part\PickupPoint
 */
class LastMileWarehouse extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string (uuid)
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return LastMileWarehouse
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return LastMileWarehouse
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

}