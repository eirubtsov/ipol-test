<?php
namespace Ipol\Demo\Api\Entity\UniversalPart;

use Ipol\Demo\Api\Entity\AbstractCollection;

/**
 * Class WarehouseEntityList
 * @package Ipol\Demo\Api
 * @subpackage Entity\UniversalPart
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