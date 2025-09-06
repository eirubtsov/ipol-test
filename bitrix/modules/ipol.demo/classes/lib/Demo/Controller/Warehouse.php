<?php


namespace Ipol\Demo\Demo\Controller;


use Ipol\Demo\Api\Entity\Request\Part\Warehouse\WarehouseElemList;
use \Ipol\Demo\Api\Entity\Request\Warehouse as WarehouseRequest;
use Ipol\Demo\Demo\Entity\WarehouseResult;

/**
 * Class Warehouse
 * @package Ipol\Demo\Demo
 * @subpackage Controller
 */
class Warehouse extends AutomatedCommonRequest
{
    /**
     * Warehouse constructor.
     * @param WarehouseElemList $warehouses
     */
    public function __construct(WarehouseElemList $warehouses)
    {
        parent::__construct(new WarehouseResult());

        $data = new WarehouseRequest();
        $data->setWarehouses($warehouses);
        $this->setRequestObj($data);
    }

}