<?php
namespace Ipol\Demo\Demo\Controller;

use Ipol\Demo\Api\Entity\Request\WarehousesInfo as RequestObj;
use Ipol\Demo\Demo\Entity\WarehousesInfoResult as ResultObj;

/**
 * Class WarehousesInfo
 * @package Ipol\Demo\Demo\Controller
 */
class WarehousesInfo extends AutomatedCommonRequest
{
    /**
     * WarehousesInfo constructor.
     * @param ResultObj $resultObj
     * @param int $page
     */
    public function __construct(ResultObj $resultObj, int $page)
    {
        parent::__construct($resultObj);
        $this->requestObj = new RequestObj();
        $this->requestObj->setPage($page);
    }
}