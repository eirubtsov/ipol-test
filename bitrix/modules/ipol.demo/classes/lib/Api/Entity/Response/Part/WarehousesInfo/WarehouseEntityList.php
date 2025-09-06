<?php
namespace Ipol\Demo\Api\Entity\Response\Part\WarehousesInfo;

use Ipol\Demo\Api\Entity\AbstractCollection;
use Ipol\Demo\Api\Entity\Response\Part\Common\WarehouseEntity;

/**
 * Class WarehouseEntityList
 * @package Ipol\Demo\Api
 * @subpackage Response
 * @method WarehouseEntity getFirst
 * @method WarehouseEntity getNext
 * @method WarehouseEntity getLast
 */
class WarehouseEntityList extends AbstractCollection
{
    protected $WarehouseEntities;

    public function __construct()
    {
        parent::__construct('WarehouseEntities');
        $this->setChildClass(WarehouseEntity::class);
    }
}