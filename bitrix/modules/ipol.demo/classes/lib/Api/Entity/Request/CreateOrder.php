<?php


namespace Ipol\Demo\Api\Entity\Request;


use Ipol\Demo\Api\Entity\Request\Part\CreateOrder\PartnerOrderList;

/**
 * Class CreateOrder
 * @package Ipol\Demo\Api\Entity\Request
 */
class CreateOrder extends AbstractRequest
{
    /**
     * @var PartnerOrderList
     */
    protected $partnerOrders;

    /**
     * @return PartnerOrderList
     */
    public function getPartnerOrders()
    {
        return $this->partnerOrders;
    }

    /**
     * @param PartnerOrderList $partnerOrders
     * @return CreateOrder
     */
    public function setPartnerOrders($partnerOrders)
    {
        $this->partnerOrders = $partnerOrders;
        return $this;
    }

}