<?php


namespace Ipol\Demo\Demo\Controller;


use Ipol\Demo\Api\Entity\Request\GetOrderHistory;
use Ipol\Demo\Demo\Entity\OrderHistoryResult;

/**
 * Class OrderHistoryController
 * @package Ipol\Demo\Demo
 * @subpackage Controller
 */
class OrderHistoryController extends AutomatedCommonRequest
{
    /**
     * OrderHistoryController constructor.
     * @param string $id
     * @param string $type
     */
    public function __construct(string $id, string $type)
    {
        parent::__construct(new OrderHistoryResult());

        $data = new GetOrderHistory();
        switch ($type) {
            case "uuid":
                $data->setOrderId($id);
                break;
            case "senderOrderId":
                $data->setSenderOrderId($id);
                break;
        }
        $this->setRequestObj($data);
    }
}