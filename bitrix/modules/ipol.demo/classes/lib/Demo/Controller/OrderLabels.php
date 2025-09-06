<?php
namespace Ipol\Demo\Demo\Controller;

use Ipol\Demo\Api\Entity\Request\OrderLabelsById;
use Ipol\Demo\Api\Entity\Request\OrderLabelsByNumber;
use Ipol\Demo\Demo\Entity\OrderLabelsResult;
use Ipol\Demo\Demo\DemoApplication;

/**
 * Class OrderLabels
 * @package Ipol\Demo\Demo
 * @subpackage Controller
 */
class OrderLabels extends AutomatedCommonRequest
{
    /**
     * OrderLabels constructor.
     * @param string[] $orderNumbers
     * @param string $type
     */
    public function __construct(array $orderNumbers, string $type)
    {
        parent::__construct(new OrderLabelsResult());

        if (!is_array($orderNumbers))
            $orderNumbers = [$orderNumbers];

        switch ($type) {
            case DemoApplication::ORDER_ID_TYPE_5P:
                $data = new OrderLabelsById($orderNumbers);
                break;
            case DemoApplication::ORDER_ID_TYPE_CMS:
                $data = new OrderLabelsByNumber($orderNumbers);
                break;
        }
        $this->setRequestObj($data);
    }
}