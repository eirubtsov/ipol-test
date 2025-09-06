<?php
namespace Ipol\Demo\Api\Entity\Response;

use Ipol\Demo\Api\Entity\Response\Part\Common\WarehouseEntity;

/**
 * Class WarehouseInfo
 * @package Ipol\Demo\Api\Entity\Response
 */
class WarehouseInfo extends AbstractResponse
{
    /**
     * @var WarehouseEntity
     */
    protected $warehouseEntity;

    /**
     * @return WarehouseEntity
     */
    public function getWarehouseEntity(): WarehouseEntity
    {
        return $this->warehouseEntity;
    }

    /**
     * @param array $array
     * @return WarehouseInfo
     */
    public function setWarehouseEntity(array $array): WarehouseInfo
    {
        $this->warehouseEntity = new WarehouseEntity($array);
        return $this;
    }

    public function setFields($fields): WarehouseInfo
    {
        return parent::setFields(['warehouseEntity' => $fields]);
    }
}