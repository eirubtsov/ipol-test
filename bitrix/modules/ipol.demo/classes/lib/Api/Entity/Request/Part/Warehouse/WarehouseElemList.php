<?php


namespace Ipol\Demo\Api\Entity\Request\Part\Warehouse;


use Ipol\Demo\Api\Entity\AbstractCollection;

class WarehouseElemList extends AbstractCollection
{

    protected $WarehouseElems;

    public function __construct()
    {
        parent::__construct('WarehouseElems');
    }

    /**
     * @return WarehouseElem
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return WarehouseElem
     */
    public function getNext(){
        return parent::getNext();
    }
}