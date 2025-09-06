<?php


namespace Ipol\Demo\Demo\Controller;


use Ipol\Demo\Api\Entity\Request\GetOrderStatus;
use Ipol\Demo\Api\Entity\Request\Part\GetOrderStatus\OrderStatus;
use Ipol\Demo\Api\Entity\Request\Part\GetOrderStatus\OrderStatusList;
use Ipol\Demo\Demo\Entity\OrderStatusResult;

/**
 * Class OrderStatusController
 * @package Ipol\Demo\Demo
 * @subpackage Controller
 */
class OrderStatusController extends AutomatedCommonRequest
{
    /**
     * OrderStatusController constructor.
     * @param array $arNumbers
     * @param string $type
     */
    public function __construct(array $arNumbers, string $type)
    {
        parent::__construct(new OrderStatusResult());

        $data = new GetOrderStatus();
        $orderCollection = new OrderStatusList();
        switch ($type) {
            case "uuid":
                foreach ($arNumbers as $uuid) {
                    $order = new OrderStatus();
                    $order->setOrderId($uuid);
                    $orderCollection->add($order);
                }
                break;
            case "senderOrderId":
                foreach ($arNumbers as $number) {
                    $order = new OrderStatus();
                    $order->setSenderOrderId($number);
                    $orderCollection->add($order);
                }
                break;
        }
        $data->setOrderStatuses($orderCollection);
        $this->setRequestObj($data);
    }

}