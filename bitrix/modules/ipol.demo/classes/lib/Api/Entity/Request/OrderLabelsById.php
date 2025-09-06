<?php
namespace Ipol\Demo\Api\Entity\Request;

/**
 * Class OrderLabelsById
 * @package Ipol\Demo\Api\Entity\Request
 */
class OrderLabelsById extends AbstractRequest
{
    /**
     * @var string[] Demo uuids
     */
    protected $orderIds;

    public function __construct(array $orderIds)
    {
        parent::__construct();
        $this->setOrderIds($orderIds);
    }

    /**
     * @return string[]
     */
    public function getOrderIds(): array
    {
        return $this->orderIds;
    }

    /**
     * @param string[] $orderIds
     * @return OrderLabelsById
     */
    public function setOrderIds(array $orderIds): OrderLabelsById
    {
        $this->orderIds = $orderIds;
        return $this;
    }
}