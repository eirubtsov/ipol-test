<?php


namespace Ipol\Demo\Demo\Controller;


use \Ipol\Demo\Api\Entity\Request\PickupPoints as PointsRequest;
use Ipol\Demo\Demo\Entity\PickupPointsResult;

/**
 * Class PickupPoints
 * @package Ipol\Demo\Demo
 * @subpackage Controller
 */
class PickupPoints extends AutomatedCommonRequest
{
    /**
     * PickupPoints constructor.
     * @param int $pageNum
     * @param int $pageSize
     */
    public function __construct(int $pageNum = 0, int $pageSize = 1000)
    {
        parent::__construct(new PickupPointsResult());
        $data = new PointsRequest();
        $data->setPageNumber($pageNum)
            ->setPageSize($pageSize);

        $this->setRequestObj($data);
    }

}
